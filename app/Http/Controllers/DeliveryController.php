<?php

namespace App\Http\Controllers;

use App\Models\CreativeJob;
use App\Models\EmployeeNotification;
use App\Models\WorkflowStage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $jobs = CreativeJob::query()
            ->with(['client', 'project', 'category', 'currentWorkflowStage'])
            ->when($this->shouldRestrictToClientServiceJobs($request), function ($query) use ($request): void {
                $query->where(function ($query) use ($request): void {
                    $query
                        ->where('responsible_user_id', $request->user()->id)
                        ->orWhereHas('client.clientServiceUsers', fn ($clientService) => $clientService->where('users.id', $request->user()->id));
                });
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('job_number', 'ilike', "%{$search}%")
                        ->orWhere('title', 'ilike', "%{$search}%")
                        ->orWhereHas('client', fn ($client) => $client->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('project', fn ($project) => $project->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->where('is_archived', false)
            ->where(function ($query): void {
                $query->whereNull('delivery_review_status')
                    ->orWhere('delivery_review_status', '!=', 'published');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('deliveries.index', [
            'jobs' => $jobs,
            'search' => $search,
        ]);
    }

    public function edit(CreativeJob $job): View
    {
        $job->load(['client', 'project', 'category', 'currentWorkflowStage']);

        return view('deliveries.edit', [
            'job' => $job,
            'workflowStages' => WorkflowStage::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, CreativeJob $job): RedirectResponse
    {
        $data = $request->validate([
            'current_workflow_stage_id' => ['nullable', 'exists:workflow_stages,id'],
            'completion_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'dropbox_folder_path' => ['nullable', 'string', 'max:1000'],
            'final_delivery_path' => ['nullable', 'required_if:delivery_review_status,published', 'string', 'max:1000'],
            'client_notes' => ['nullable', 'string', 'max:2000'],
            'final_delivered_at' => ['nullable', 'date'],
            'delivery_review_status' => ['required', 'in:draft,checked,published'],
        ]);

        if (
            $data['delivery_review_status'] === 'published'
            && ! $this->canPublishDelivery($request)
        ) {
            return back()
                ->withInput()
                ->withErrors(['delivery_review_status' => 'Only Admin, Account, or Client Service employees can publish deliveries to the client portal.']);
        }

        if (in_array($data['delivery_review_status'], ['checked', 'published'], true)) {
            $data['delivery_reviewed_by'] = $request->user()->id;
            $data['delivery_reviewed_at'] = now();
        }

        if ($data['delivery_review_status'] === 'published') {
            $data['delivery_published_at'] = $job->delivery_published_at ?? now();
            $data['final_delivered_at'] = $data['final_delivered_at'] ?? now();
            $data['completion_percentage'] = 100;

            $completedStage = WorkflowStage::query()->where('code', 'COMPLETED')->first();

            if ($completedStage) {
                $data['current_workflow_stage_id'] = $completedStage->id;
            }
        } else {
            $data['delivery_published_at'] = null;
        }

        if (
            blank($data['final_delivery_path'] ?? null)
            && filled($job->employee_handover_link)
            && in_array($data['delivery_review_status'], ['checked', 'published'], true)
        ) {
            $data['final_delivery_path'] = $job->employee_handover_link;
        }

        $previousStatus = $job->delivery_review_status;

        $job->update($data);

        if ($previousStatus !== $job->delivery_review_status && $job->delivery_review_status === 'checked') {
            $job->loadMissing(['client.clientServiceUsers']);

            $notifyUserIds = collect([$job->responsible_user_id])
                ->merge($job->client?->clientServiceUsers?->pluck('id') ?? [])
                ->filter()
                ->unique()
                ->reject(fn ($userId) => (int) $userId === (int) $request->user()->id)
                ->values();

            foreach ($notifyUserIds as $userId) {
                EmployeeNotification::query()->create([
                    'user_id' => $userId,
                    'creative_job_id' => $job->id,
                    'type' => 'traffic_delivery_checked',
                    'title' => 'Traffic checked final output',
                    'body' => $job->job_number.' — '.$job->title.' is ready for Client Service approval and client portal publishing.',
                ]);
            }
        }

        if ($job->delivery_review_status === 'published') {
            return redirect()
                ->route('completed-jobs.index')
                ->with('success', 'Delivery published to the client portal and moved to Completed Jobs.');
        }

        return redirect()->route('deliveries.edit', $job)->with('success', 'Delivery details saved.');
    }

    private function canPublishDelivery(Request $request): bool
    {
        $user = $request->user();
        $roleCode = str($user?->role?->code ?? '')->lower()->replace(['-', ' '], '_')->toString();
        $jobTitle = str($user?->job_title ?? '')->lower()->toString();

        return str_contains($roleCode, 'admin')
            || str_contains($roleCode, 'account')
            || str_contains($roleCode, 'client_service')
            || str_contains($roleCode, 'customer_service')
            || str_contains($jobTitle, 'client service')
            || str_contains($jobTitle, 'customer service')
            || str_contains($jobTitle, 'account manager');
    }

    private function shouldRestrictToClientServiceJobs(Request $request): bool
    {
        $user = $request->user();
        $roleCode = str($user?->role?->code ?? '')->lower()->replace(['-', ' '], '_')->toString();
        $jobTitle = str($user?->job_title ?? '')->lower()->toString();

        $isBroadManager = str_contains($roleCode, 'super_admin')
            || str_contains($roleCode, 'general_manager')
            || str_contains($roleCode, 'operations_manager')
            || str_contains($roleCode, 'traffic_manager')
            || str_contains($roleCode, 'hr');

        $isClientService = str_contains($roleCode, 'client_service')
            || str_contains($roleCode, 'account')
            || str_contains($jobTitle, 'client service')
            || str_contains($jobTitle, 'account manager');

        return $isClientService && ! $isBroadManager;
    }
}
