<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\JobCategory;
use App\Models\JobStatus;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;

use App\Modules\Jobs\Requests\StoreJobRequest;
use App\Modules\Jobs\Requests\AssignJobRequest;
use App\Modules\Jobs\Services\JobService;

class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    /**
     * Jobs List
     */
    public function index()
    {
        $jobs = $this->jobService->all();

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Create Job Form
     */
    public function create()
    {
        return view('jobs.create', [

            'clients' => Client::orderBy('name')->get(),

            'projects' => Project::orderBy('name')->get(),

            'categories' => JobCategory::orderBy('name')->get(),

            'statuses' => JobStatus::orderBy('sort_order')->get(),

        ]);
    }

    /**
     * Store Job
     */
    public function store(StoreJobRequest $request)
    {
        $job = $this->jobService->create(
            $request->validated()
        );

        return redirect()
            ->route('jobs.show', $job->id)
            ->with(
                'success',
                'Creative Job created successfully.'
            );
    }

    /**
     * Job Details
     */
    public function show(int $id)
    {
        $job = $this->jobService->find($id);

        abort_unless($job, 404);

        $teams = Team::orderBy('name')->get();

        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('jobs.show', [

            'job' => $job,

            'teams' => $teams,

            'users' => $users,

        ]);
    }

    /**
     * Assign Job
     */
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
            ->with(
                'success',
                'Job assigned successfully.'
            );
    }

    /**
     * Edit
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update
     */
    public function update(int $id)
    {
        //
    }

    /**
     * Delete
     */
    public function destroy(int $id)
    {
        //
    }
}