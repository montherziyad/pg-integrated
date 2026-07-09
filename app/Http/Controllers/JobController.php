<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\EmployeeNotification;
use App\Models\JobCategory;
use App\Models\JobActivity;
use App\Models\JobStatus;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\WorkflowStage;
use App\Modules\Jobs\Requests\AssignJobRequest;
use App\Modules\Jobs\Requests\StoreJobRequest;
use App\Modules\Jobs\Requests\UpdateJobRequest;
use App\Modules\Jobs\Requests\UploadJobAttachmentsRequest;
use App\Modules\Jobs\Services\JobService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $jobs = $this->jobService->all($request->user(), $search);
        $isHandoverView = $request->routeIs('handovers.*');

        return view('jobs.index', compact('jobs', 'isHandoverView', 'search'));
    }

    public function create()
    {
        return view('jobs.create', [
            'clients' => Client::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'categories' => JobCategory::orderBy('name')->get(),
            'statuses' => JobStatus::orderBy('sort_order')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreJobRequest $request)
    {
        $job = $this->jobService->create(
            $request->validated()
        );

        return redirect()
            ->route('jobs.show', $job->id)
            ->with('success', 'Creative Job created successfully.');
    }

    public function show(int $id)
    {
        $job = $this->jobService->find($id);

        abort_unless($job, 404);

        $teams = Team::orderBy('name')->get();

        $users = User::where('is_active', true)
            ->with(['employeeLeaves' => fn ($query) => $query->where('status', 'approved')->whereDate('starts_at', '<=', now()->toDateString())->whereDate('ends_at', '>=', now()->toDateString())])
            ->orderBy('name')
            ->get();

        abort_unless($job->fresh(['assignments'])->isAssignedTo(request()->user()) || request()->user()?->canAccessScreen('traffic_board') || request()->user()?->canAccessScreen('team_workload') || request()->user()?->canAccessScreen('deliveries') || request()->user()?->canAccessScreen('clients'), 403);

        // Prepare selected users data for the assignment UI (id + name)
        $assignedDesigners = $job->assignedDesigners()->map(fn($u) => ['id' => $u->id, 'name' => $u->name])->values()->toArray();
        $assignedSupervisors = $job->assignmentLeads()->map(fn($u) => ['id' => $u->id, 'name' => $u->name])->values()->toArray();

        return view('jobs.show', compact(
            'job',
            'teams',
            'users',
            'assignedDesigners',
            'assignedSupervisors'
        ));
    }

    public function assign(
        AssignJobRequest $request,
        CreativeJob $job
    ) {
        $this->jobService->assign(
            $job,
            $request->validated()
        );

        return redirect()
            ->route('jobs.show', $job->id)
            ->with('success', 'Job assigned successfully.');
    }

    public function uploadAttachments(
        UploadJobAttachmentsRequest $request,
        CreativeJob $job
    ) {
        $this->jobService->uploadAttachments(
            $job,
            $request->file('attachments', [])
        );

        return redirect()
            ->route('jobs.show', $job->id)
            ->with('success', 'Attachments uploaded successfully.');
    }


    public function handover(Request $request, CreativeJob $job): RedirectResponse
    {
        $job->load('assignments');

        abort_unless(
            $job->isAssignedTo($request->user())
                || $request->user()?->canAccessScreen('traffic_board')
                || $request->user()?->canAccessScreen('team_workload'),
            403
        );

        $data = $request->validate([
            'employee_handover_link' => ['nullable', 'url', 'max:2000'],
            'employee_handover_notes' => ['nullable', 'string', 'max:3000'],
            'handover_files' => ['nullable', 'array'],
            'handover_files.*' => ['file', 'max:51200', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,psd,ai,aep,png,jpg,jpeg,svg,mp4,mov'],
        ]);

        if (blank($data['employee_handover_link'] ?? null) && ! $request->hasFile('handover_files')) {
            return back()->withErrors(['employee_handover_link' => 'Add a handover link or upload at least one handover file.'])->withInput();
        }

        foreach ($request->file('handover_files', []) as $file) {
            $path = $file->store('creative-jobs/'.$job->job_number.'/employee-handovers', 'local');

            Asset::query()->create([
                'creative_job_id' => $job->id,
                'uploaded_by' => Auth::id(),
                'file_name' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'storage_type' => 'local',
                'storage_path' => $path,
                'version' => 1,
                'asset_stage' => 'EMPLOYEE_HANDOVER',
                'is_final' => false,
                'notes' => $data['employee_handover_notes'] ?? null,
            ]);
        }

        $job->update([
            'employee_handover_status' => 'submitted_to_traffic',
            'employee_handover_link' => $data['employee_handover_link'] ?? $job->employee_handover_link,
            'employee_handover_notes' => $data['employee_handover_notes'] ?? null,
            'employee_handover_submitted_by' => $request->user()->id,
            'employee_handover_submitted_at' => now(),
        ]);

        JobActivity::query()->create([
            'creative_job_id' => $job->id,
            'user_id' => $request->user()->id,
            'activity' => 'EMPLOYEE_HANDOVER_SUBMITTED',
            'activity_type' => 'EMPLOYEE_HANDOVER_SUBMITTED',
            'description' => 'Employee submitted handover to traffic review.',
            'activity_at' => now(),
        ]);

        $trafficUserIds = User::query()
            ->whereHas('role', fn ($role) => $role->whereIn('code', ['TRAFFIC_MANAGER', 'OPERATIONS_MANAGER', 'SUPER_ADMIN']))
            ->pluck('id')
            ->merge([$job->traffic_manager_id, $job->project_manager_id])
            ->filter()
            ->unique()
            ->reject(fn ($userId) => (int) $userId === (int) $request->user()->id)
            ->values();

        foreach ($trafficUserIds as $userId) {
            EmployeeNotification::query()->create([
                'user_id' => $userId,
                'creative_job_id' => $job->id,
                'type' => 'employee_handover_submitted',
                'title' => 'Handover submitted to Traffic',
                'body' => $job->job_number.' — '.$job->title.' was submitted by '.$request->user()->name.' for traffic review.',
            ]);
        }

        return redirect()->route('jobs.show', $job)->with('success', 'Handover sent to traffic successfully.');
    }

    public function approveTrafficHandover(Request $request, CreativeJob $job): RedirectResponse
    {
        abort_unless(
            $request->user()?->canAccessScreen('traffic_board')
                || $request->user()?->canAccessScreen('deliveries')
                || $request->user()?->canAccessScreen('team_workload'),
            403
        );

        if ($job->employee_handover_status !== 'submitted_to_traffic') {
            return back()->withErrors([
                'handover' => 'No submitted handover is available for Traffic review.',
            ]);
        }

        $job->loadMissing(['client.clientServiceUsers']);

        $previousStatus = $job->delivery_review_status;

        $job->update([
            'delivery_review_status' => 'checked',
            'final_delivery_path' => $job->final_delivery_path ?: $job->employee_handover_link,
            'delivery_reviewed_by' => $request->user()->id,
            'delivery_reviewed_at' => now(),
            'delivery_published_at' => null,
        ]);

        JobActivity::query()->create([
            'creative_job_id' => $job->id,
            'user_id' => $request->user()->id,
            'activity' => 'TRAFFIC_HANDOVER_APPROVED',
            'activity_type' => 'TRAFFIC_HANDOVER_APPROVED',
            'description' => 'Traffic approved the handover and sent it to Client Service review.',
            'activity_at' => now(),
        ]);

        if ($previousStatus !== 'checked') {
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
                    'title' => 'Traffic approved handover',
                    'body' => $job->job_number.' — '.$job->title.' was approved by Traffic and is ready for Client Service review.',
                ]);
            }
        }

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', 'Handover approved and sent to Client Service.');
    }

    public function publishFromClientService(Request $request, CreativeJob $job): RedirectResponse
    {
        abort_unless($this->canClientServiceReview($request), 403);

        if ($job->delivery_review_status !== 'checked') {
            return back()->withErrors([
                'delivery_review_status' => 'This job must be checked by Traffic before Client Service can publish it.',
            ]);
        }

        $data = $request->validate([
            'client_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $job->update([
            'delivery_review_status' => 'published',
            'final_delivery_path' => $job->final_delivery_path ?: $job->employee_handover_link,
            'client_notes' => $data['client_notes'] ?? $job->client_notes,
            'delivery_reviewed_by' => $request->user()->id,
            'delivery_reviewed_at' => now(),
            'delivery_published_at' => now(),
            'final_delivered_at' => $job->final_delivered_at ?? now(),
            'completion_percentage' => max((int) $job->completion_percentage, 100),
        ]);

        JobActivity::query()->create([
            'creative_job_id' => $job->id,
            'user_id' => $request->user()->id,
            'activity' => 'CLIENT_SERVICE_PUBLISHED',
            'activity_type' => 'CLIENT_SERVICE_PUBLISHED',
            'description' => 'Client Service approved the final delivery and published it to the client portal.',
            'activity_at' => now(),
        ]);

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', 'Delivery approved and published to the client portal.');
    }

    public function requestClientServiceRevision(Request $request, CreativeJob $job): RedirectResponse
    {
        abort_unless($this->canClientServiceReview($request), 403);

        if ($job->delivery_review_status !== 'checked') {
            return back()->withErrors([
                'delivery_review_status' => 'Only checked deliveries can be sent back for revision.',
            ]);
        }

        $data = $request->validate([
            'revision_notes' => ['required', 'string', 'min:5', 'max:3000'],
            'revision_files' => ['nullable', 'array'],
            'revision_files.*' => ['file', 'max:51200', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,psd,ai,aep,png,jpg,jpeg,svg,mp4,mov'],
        ]);

        foreach ($request->file('revision_files', []) as $file) {
            $path = $file->store('creative-jobs/'.$job->job_number.'/client-service-revisions', 'local');

            Asset::query()->create([
                'creative_job_id' => $job->id,
                'uploaded_by' => Auth::id(),
                'file_name' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'storage_type' => 'local',
                'storage_path' => $path,
                'version' => ((int) $job->revision_count) + 1,
                'asset_stage' => 'REVIEW',
                'is_final' => false,
                'notes' => $data['revision_notes'],
            ]);
        }

        $job->loadMissing('assignments.assignee');

        $job->update([
            'delivery_review_status' => 'draft',
            'employee_handover_status' => 'not_submitted',
            'employee_handover_notes' => $data['revision_notes'],
            'delivery_published_at' => null,
            'revision_count' => ((int) $job->revision_count) + 1,
            'client_notes' => trim(($job->client_notes ? $job->client_notes."\n\n" : '').'Client Service revision request: '.$data['revision_notes']),
        ]);

        JobActivity::query()->create([
            'creative_job_id' => $job->id,
            'user_id' => $request->user()->id,
            'activity' => 'CLIENT_SERVICE_REVISION_REQUESTED',
            'activity_type' => 'CLIENT_SERVICE_REVISION_REQUESTED',
            'description' => 'Client Service requested revisions: '.$data['revision_notes'],
            'activity_at' => now(),
        ]);

        $notifyUserIds = $job->assignments
            ->pluck('user_id')
            ->merge($job->assignments->pluck('supervisor_id'))
            ->merge([$job->traffic_manager_id, $job->project_manager_id])
            ->filter()
            ->unique()
            ->reject(fn ($userId) => (int) $userId === (int) $request->user()->id)
            ->values();

        foreach ($notifyUserIds as $userId) {
            EmployeeNotification::query()->create([
                'user_id' => $userId,
                'creative_job_id' => $job->id,
                'type' => 'client_service_revision_requested',
                'title' => 'Revision requested by Client Service',
                'body' => $job->job_number.' — '.$job->title.' needs revision. Notes: '.$data['revision_notes'],
            ]);
        }

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', 'Revision request sent to Traffic and the assigned production team.');
    }

    public function confirmProductionDue(Request $request, CreativeJob $job): RedirectResponse
    {
        $job->load(['assignments', 'client.clientServiceUsers']);

        abort_unless(
            $job->isAssignedTo($request->user())
                || $request->user()?->canAccessScreen('traffic_board')
                || $request->user()?->canAccessScreen('team_workload'),
            403
        );

        $data = $request->validate([
            'production_due_date' => ['required', 'date', 'after_or_equal:today'],
            'production_due_time' => ['required', 'date_format:H:i'],
            'production_due_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $productionDueAt = Carbon::createFromFormat(
            'Y-m-d H:i',
            $data['production_due_date'].' '.$data['production_due_time'],
            config('app.timezone')
        );

        if ($productionDueAt->isPast()) {
            return back()
                ->withInput()
                ->withErrors(['production_due_time' => 'Please choose a future date and time.']);
        }

        $job->update([
            'production_due_at' => $productionDueAt,
            'production_due_confirmed_by' => $request->user()->id,
            'production_due_confirmed_at' => now(),
            'production_due_notes' => $data['production_due_notes'] ?? null,
        ]);

        JobActivity::query()->create([
            'creative_job_id' => $job->id,
            'user_id' => $request->user()->id,
            'activity' => 'PRODUCTION_DUE_CONFIRMED',
            'activity_type' => 'PRODUCTION_DUE_CONFIRMED',
            'description' => 'Production team confirmed expected delivery date: '.$productionDueAt->format('Y-m-d H:i'),
            'activity_at' => now(),
        ]);

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
                'type' => 'production_due_confirmed',
                'title' => 'Production delivery date confirmed',
                'body' => $job->job_number.' — '.$job->title.' will be ready on '.$productionDueAt->format('d M Y, h:i A').'.',
            ]);
        }

        return redirect()->route('jobs.show', $job)->with('success', 'Delivery date confirmed and Client Service notified.');
    }

    public function edit(int $id)
    {
        $job = $this->jobService->find($id);

        abort_unless($job, 404);

        return view('jobs.edit', [
            'job' => $job,
            'clients' => Client::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'categories' => JobCategory::orderBy('name')->get(),
            'statuses' => JobStatus::orderBy('sort_order')->get(),
            'workflowStages' => WorkflowStage::orderBy('sort_order')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function downloadAttachment(Asset $asset)
    {
        if (! Storage::disk($asset->storage_type)->exists($asset->storage_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk($asset->storage_type)->download(
            $asset->storage_path,
            $asset->original_name
        );
    }

    public function update(UpdateJobRequest $request, CreativeJob $job)
    {
        $this->jobService->update($job, $request->validated());

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', 'Creative Job updated successfully.');
    }

    public function destroy(int $id)
    {
        //
    }

    private function canClientServiceReview(Request $request): bool
    {
        $user = $request->user();
        $roleCode = str($user?->role?->code ?? '')->lower()->replace(['-', ' '], '_')->toString();
        $jobTitle = str($user?->job_title ?? '')->lower()->toString();

        return $user?->canAccessScreen('deliveries')
            || $user?->canAccessScreen('clients')
            || str_contains($roleCode, 'admin')
            || str_contains($roleCode, 'client_service')
            || str_contains($roleCode, 'account')
            || str_contains($jobTitle, 'client service')
            || str_contains($jobTitle, 'account manager');
    }
}
