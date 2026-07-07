<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Client;
use App\Models\CreativeJob;
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

class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    public function index(Request $request)
    {
        $jobs = $this->jobService->all($request->user());
        $isHandoverView = $request->routeIs('handovers.*');

        return view('jobs.index', compact('jobs', 'isHandoverView'));
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

        return view('jobs.show', compact(
            'job',
            'teams',
            'users'
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
            'activity_type' => 'EMPLOYEE_HANDOVER_SUBMITTED',
            'description' => 'Employee submitted handover to traffic review.',
            'activity_at' => now(),
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Handover sent to traffic successfully.');
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
}
