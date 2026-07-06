<?php

namespace App\Http\Controllers;

use App\Models\CrmContact;
use App\Models\MarketingCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarketingCampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = MarketingCampaign::query()
            ->with(['creator', 'approver'])
            ->withCount('recipients')
            ->latest()
            ->paginate(20);

        $recipientQuery = DB::table('marketing_campaign_recipients');
        $sent = (clone $recipientQuery)->whereNotNull('sent_at')->count();
        $responded = (clone $recipientQuery)->whereNotNull('responded_at')->count();

        return view('marketing.index', [
            'campaigns' => $campaigns,
            'stats' => [
                'total' => MarketingCampaign::count(),
                'draft' => MarketingCampaign::where('status', 'draft')->count(),
                'pending' => MarketingCampaign::where('status', 'pending_approval')->count(),
                'approved' => MarketingCampaign::whereIn('status', ['approved', 'scheduled', 'active'])->count(),
                'completed' => MarketingCampaign::where('status', 'completed')->count(),
                'recipients' => (clone $recipientQuery)->count(),
                'sent' => $sent,
                'responded' => $responded,
                'response_rate' => $sent > 0 ? round(($responded / $sent) * 100, 1) : 0,
            ],
        ]);
    }

    public function create(): View
    {
        return view('marketing.form', [
            'campaign' => new MarketingCampaign,
            'contacts' => CrmContact::with('company')
                ->whereNotNull('email')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'objective' => ['required', 'string', 'max:2000'],
            'country' => ['nullable', 'string', 'max:120'],
            'industry' => ['nullable', 'string', 'max:180'],
            'email_subject' => ['required', 'string', 'max:255'],
            'message_template' => ['required', 'string', 'max:20000'],
            'cta_url' => ['nullable', 'url', 'max:1000'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'recipient_ids' => ['required', 'array', 'min:1'],
            'recipient_ids.*' => ['integer', Rule::exists('crm_contacts', 'id')->whereNotNull('email')],
        ]);

        $campaign = DB::transaction(function () use ($data, $request) {
            $campaign = MarketingCampaign::create([
                'name' => $data['name'],
                'channel' => 'outlook_email',
                'status' => 'draft',
                'objective' => $data['objective'],
                'country' => $data['country'] ?? null,
                'industry' => $data['industry'] ?? null,
                'email_subject' => $data['email_subject'],
                'message_template' => $data['message_template'],
                'cta_url' => $data['cta_url'] ?? null,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'created_by' => $request->user()->id,
            ]);

            $contacts = CrmContact::with('company')
                ->whereIn('id', array_unique($data['recipient_ids']))
                ->get();

            foreach ($contacts as $contact) {
                $campaign->recipients()->create([
                    'company_id' => $contact->company_id,
                    'contact_id' => $contact->id,
                    'status' => 'pending',
                    'meta' => [
                        'email' => strtolower($contact->email),
                        'name' => $contact->name,
                        'company' => $contact->company?->name,
                    ],
                ]);
            }

            return $campaign;
        });

        return redirect()->route('marketing.show', $campaign)->with('status', 'Outlook campaign saved as a draft.');
    }

    public function show(MarketingCampaign $campaign): View
    {
        return view('marketing.show', [
            'campaign' => $campaign->load([
                'creator',
                'approver',
                'recipients.company',
                'recipients.contact',
            ]),
        ]);
    }

    public function requestApproval(MarketingCampaign $campaign): RedirectResponse
    {
        abort_unless($campaign->status === 'draft', 422);
        abort_unless($campaign->recipients()->exists(), 422);

        $campaign->update(['status' => 'pending_approval']);

        return back()->with('status', 'Campaign submitted for admin approval. Nothing has been sent.');
    }

    public function approve(Request $request, MarketingCampaign $campaign): RedirectResponse
    {
        abort_unless($request->user()?->role?->code === 'SUPER_ADMIN', 403);
        abort_unless($campaign->status === 'pending_approval', 422);

        $campaign->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Campaign approved. Outlook sending remains disabled until Mail.Send setup is completed.');
    }
}
