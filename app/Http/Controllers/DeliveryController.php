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
            'final_delivery_path' => ['nullable', 'required_if:archive_after_delivery,1', 'string', 'max:1000'],
            'client_notes' => ['nullable', 'string', 'max:2000'],
            'final_delivered_at' => ['nullable', 'date'],
            'delivery_review_status' => ['required', 'in:draft,checked,published'],
            'archive_after_delivery' => ['nullable', 'boolean'],
        ]);

        $archiveAfterDelivery = $request->boolean('archive_after_delivery');
        unset($data['archive_after_delivery']);

        if (
            ($archiveAfterDelivery || $data['delivery_review_status'] === 'published')
            && ! $this->canPublishDelivery($request)
        ) {
            return back()
                ->withInput()
                ->withErrors(['delivery_review_status' => 'Only Admin, Account, or Client Service employees can publish deliveries to the client portal or move jobs to archive.']);
        }

        if (in_array($data['delivery_review_status'], ['checked', 'published'], true)) {
            $data['delivery_reviewed_by'] = $request->user()->id;
            $data['delivery_reviewed_at'] = now();
        }

        if ($data['delivery_review_status'] === 'published') {
            $data['delivery_published_at'] = $job->delivery_published_at ?? now();
        } else {
            $data['delivery_published_at'] = null;
        }

        if ($archiveAfterDelivery) {
            $archiveStage = WorkflowStage::query()->where('code', 'ARCHIVE')->first();
            $data['is_archived'] = true;
            $data['archived_at'] = now();
            $data['final_delivered_at'] = $data['final_delivered_at'] ?? now();
            $data['delivery_review_status'] = 'published';
            $data['delivery_published_at'] = $data['delivery_published_at'] ?? now();

            if ($archiveStage) {
                $data['current_workflow_stage_id'] = $archiveStage->id;
            }
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

        if ($archiveAfterDelivery) {
            return redirect()->route('archive.index')->with('success', 'Delivery saved and job moved to archive.');
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
}
