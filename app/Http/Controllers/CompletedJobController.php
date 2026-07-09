<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\JobActivity;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompletedJobController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $branchId = $request->query('branch_id');
        $clientId = $request->query('client_id');
        $clientServiceId = $request->query('client_service_id');

        $jobs = CreativeJob::query()
            ->with([
                'client.branch',
                'client.accountManager',
                'client.clientServiceUsers',
                'project',
                'category',
                'currentWorkflowStage',
                'deliveryReviewer',
                'responsibleUser',
            ])
            ->where('is_archived', false)
            ->where(function ($query): void {
                $query
                    ->where('delivery_review_status', 'published')
                    ->orWhereHas('currentWorkflowStage', fn ($stage) => $stage->where('code', 'COMPLETED'));
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('job_number', 'ilike', "%{$search}%")
                        ->orWhere('title', 'ilike', "%{$search}%")
                        ->orWhereHas('client', fn ($client) => $client->where('name', 'ilike', "%{$search}%")->orWhere('company_name', 'ilike', "%{$search}%"))
                        ->orWhereHas('project', fn ($project) => $project->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->when($branchId, fn ($query) => $query->whereHas('client', fn ($client) => $client->where('branch_id', $branchId)))
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->when($clientServiceId, function ($query) use ($clientServiceId): void {
                $query->where(function ($query) use ($clientServiceId): void {
                    $query
                        ->where('responsible_user_id', $clientServiceId)
                        ->orWhereHas('client', fn ($client) => $client->where('account_manager_id', $clientServiceId))
                        ->orWhereHas('client.clientServiceUsers', fn ($user) => $user->where('users.id', $clientServiceId));
                });
            })
            ->latest('delivery_published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('completed-jobs.index', [
            'jobs' => $jobs,
            'search' => $search,
            'branches' => Branch::query()->orderBy('name')->get(),
            'clients' => Client::query()->orderBy('name')->get(),
            'clientServiceUsers' => User::query()
                ->where(function ($query): void {
                    $query
                        ->where('job_title', 'ilike', '%client service%')
                        ->orWhereHas('role', fn ($role) => $role->where('code', 'ilike', '%CLIENT_SERVICE%')->orWhere('name', 'ilike', '%Client Service%'));
                })
                ->orderBy('name')
                ->get(),
            'branchId' => $branchId,
            'clientId' => $clientId,
            'clientServiceId' => $clientServiceId,
        ]);
    }

    public function reopen(Request $request, CreativeJob $job): RedirectResponse
    {
        $revisionStage = WorkflowStage::query()->where('code', 'REVISION')->first();

        $job->update([
            'current_workflow_stage_id' => $revisionStage?->id ?? $job->current_workflow_stage_id,
            'delivery_review_status' => 'draft',
            'delivery_published_at' => null,
            'employee_handover_status' => 'not_submitted',
            'completion_percentage' => min((int) ($job->completion_percentage ?? 80), 90),
            'reopened_count' => ((int) $job->reopened_count) + 1,
        ]);

        JobActivity::query()->create([
            'creative_job_id' => $job->id,
            'user_id' => $request->user()->id,
            'activity' => 'COMPLETED_JOB_REOPENED',
            'activity_type' => 'COMPLETED_JOB_REOPENED',
            'description' => 'Completed job reopened for revision or follow-up.',
            'activity_at' => now(),
        ]);

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', 'Completed job reopened for follow-up.');
    }
}
