<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\JobCategory;
use App\Models\JobStatus;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Modules\Jobs\Requests\AssignJobRequest;
use App\Modules\Jobs\Requests\StoreJobRequest;
use App\Modules\Jobs\Requests\UploadJobAttachmentsRequest;
use App\Modules\Jobs\Services\JobService;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    public function index()
    {
        $jobs = $this->jobService->all();

        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('jobs.create', [
            'clients' => Client::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'categories' => JobCategory::orderBy('name')->get(),
            'statuses' => JobStatus::orderBy('sort_order')->get(),
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
            ->orderBy('name')
            ->get();

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

    public function edit(int $id)
    {
        //
    }
    public function downloadAttachment(Asset $asset)
    {
    if (!Storage::disk($asset->storage_type)->exists($asset->storage_path)) {
        abort(404, 'File not found.');
    }

    return Storage::disk($asset->storage_type)->download(
        $asset->storage_path,
        $asset->original_name
    );  
    }   
    public function update(int $id)
    {
        //
    }

    public function destroy(int $id)
    {
        //
    }
}