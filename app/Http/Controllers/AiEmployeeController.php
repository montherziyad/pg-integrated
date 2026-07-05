<?php

namespace App\Http\Controllers;

use App\Models\AiApprovalSuggestion;
use App\Models\ClientProjectRequest;
use App\Models\CreativeJob;
use App\Models\CrmCompany;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AiEmployeeController extends Controller
{
    public function index(): View
    {
        return view('ai-employee.index', [
            'pendingSuggestions' => AiApprovalSuggestion::with('subject')->where('status', 'pending')->latest()->get(),
            'clientRequests' => ClientProjectRequest::with(['client', 'project'])->latest()->take(10)->get(),
            'supportTickets' => SupportTicket::with(['messages', 'company'])->where('status', '!=', 'closed')->latest()->take(10)->get(),
            'companies' => CrmCompany::latest()->take(10)->get(),
            'jobs' => CreativeJob::with(['client', 'project', 'currentWorkflowStage'])
                ->where('is_archived', false)
                ->latest()
                ->take(12)
                ->get(),
        ]);
    }

    public function suggestClientRequest(Request $request, ClientProjectRequest $clientRequest): RedirectResponse
    {
        AiApprovalSuggestion::updateOrCreate(
            [
                'type' => 'client_request_next_step',
                'subject_type' => $clientRequest->getMorphClass(),
                'subject_id' => $clientRequest->id,
                'status' => 'pending',
            ],
            [
                'title' => 'Review and convert client request: '.$clientRequest->title,
                'summary' => 'AI suggests reviewing this request, confirming missing details, and preparing a project/job draft after approval.',
                'payload' => [
                    'recommended_action' => 'review_request',
                    'priority' => $clientRequest->priority,
                    'draft_note' => "Client requested {$clientRequest->type}: {$clientRequest->title}. Review brief, deliverables, links, and attachments before converting.",
                    'next_steps' => [
                        'Validate brief and deliverables',
                        'Confirm timeline and country',
                        'Create project/job only after approval',
                    ],
                ],
                'created_by' => $request->user()->id,
            ],
        );

        return back()->with('status', 'AI suggestion created and is waiting for approval.');
    }

    public function suggestSupportReply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $latestMessage = $ticket->messages()->latest()->first();
        $draft = "Hello,\n\nThank you for reaching out. We received your request regarding \"{$ticket->subject}\" and our team is reviewing it. We will update you with the next step shortly.\n\nBest regards,\nPG Integrated";

        AiApprovalSuggestion::create([
            'type' => 'support_reply_draft',
            'title' => 'Draft support reply: '.$ticket->subject,
            'summary' => 'AI drafted a safe response. It will not be posted until approved.',
            'subject_type' => $ticket->getMorphClass(),
            'subject_id' => $ticket->id,
            'payload' => [
                'message' => $draft,
                'based_on' => $latestMessage?->message,
            ],
            'created_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Support reply draft created and is waiting for approval.');
    }

    public function suggestCrmLead(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'string', 'max:1000'],
            'industry' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        AiApprovalSuggestion::create([
            'type' => 'crm_lead_create',
            'title' => 'Create CRM lead: '.$data['company_name'],
            'summary' => 'AI suggests creating this company in CRM from provided research/LinkedIn information. Approval required.',
            'subject_type' => CrmCompany::class,
            'subject_id' => 0,
            'payload' => [
                'name' => $data['company_name'],
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'website' => $data['website'] ?? null,
                'industry' => $data['industry'] ?? null,
                'country' => $data['country'] ?? null,
                'source' => 'ai_employee_review',
                'status' => 'new',
                'lead_score' => 50,
                'notes' => $data['notes'] ?? null,
            ],
            'created_by' => $request->user()->id,
        ]);

        return back()->with('status', 'CRM lead suggestion created and is waiting for approval.');
    }

    public function approve(Request $request, AiApprovalSuggestion $suggestion): RedirectResponse
    {
        abort_unless($suggestion->status === 'pending', 422);

        match ($suggestion->type) {
            'support_reply_draft' => $this->approveSupportReply($request, $suggestion),
            'crm_lead_create' => $this->approveCrmLead($request, $suggestion),
            default => null,
        };

        $suggestion->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'AI suggestion approved and applied.');
    }

    public function reject(Request $request, AiApprovalSuggestion $suggestion): RedirectResponse
    {
        abort_unless($suggestion->status === 'pending', 422);

        $suggestion->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'decision_notes' => $request->input('decision_notes'),
        ]);

        return back()->with('status', 'AI suggestion rejected.');
    }

    private function approveSupportReply(Request $request, AiApprovalSuggestion $suggestion): void
    {
        SupportMessage::create([
            'ticket_id' => $suggestion->subject_id,
            'user_id' => $request->user()->id,
            'sender_type' => 'agent',
            'message' => data_get($suggestion->payload, 'message'),
            'meta' => ['approved_ai_suggestion_id' => $suggestion->id],
        ]);
    }

    private function approveCrmLead(Request $request, AiApprovalSuggestion $suggestion): void
    {
        $company = CrmCompany::create([
            'name' => data_get($suggestion->payload, 'name'),
            'industry' => data_get($suggestion->payload, 'industry'),
            'country' => data_get($suggestion->payload, 'country'),
            'website' => data_get($suggestion->payload, 'website'),
            'linkedin_url' => data_get($suggestion->payload, 'linkedin_url'),
            'source' => data_get($suggestion->payload, 'source'),
            'status' => data_get($suggestion->payload, 'status', 'new'),
            'lead_score' => data_get($suggestion->payload, 'lead_score', 50),
            'owner_id' => $request->user()->id,
        ]);

        if (filled(data_get($suggestion->payload, 'notes'))) {
            $company->activities()->create([
                'user_id' => $request->user()->id,
                'type' => 'note',
                'channel' => 'ai_employee',
                'summary' => Str::limit(data_get($suggestion->payload, 'notes'), 180),
                'body' => data_get($suggestion->payload, 'notes'),
                'activity_at' => now(),
                'meta' => ['approved_ai_suggestion_id' => $suggestion->id],
            ]);
        }
    }
}
