<?php

namespace App\Http\Controllers;
use App\Models\CreativeJob;
use App\Models\WorkflowStage;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Project;
use App\Models\JobCategory;
use App\Models\JobStatus;
use Illuminate\Http\Request;
use App\Modules\Jobs\Services\JobService;

class JobController extends Controller
{
    public function index()
    {
        return view('jobs.index');
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $categories = JobCategory::orderBy('name')->get();
        $statuses = JobStatus::orderBy('sort_order')->get();

        return view('jobs.create', compact(
            'clients',
            'projects',
            'categories',
            'statuses'
        ));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'client_id' => ['required', 'exists:clients,id'],
        'project_id' => ['nullable', 'exists:projects,id'],
        'job_category_id' => ['nullable', 'exists:job_categories,id'],
        'priority' => ['required', 'in:LOW,MEDIUM,HIGH,URGENT,CRITICAL'],
        'first_draft_due_at' => ['nullable', 'date'],
        'final_due_at' => ['nullable', 'date'],
        'estimated_hours' => ['nullable', 'numeric', 'min:0'],
        'brief' => ['nullable', 'string'],
        'attachments.*' => ['nullable', 'file', 'max:51200'],
    ]);

    $trafficStage = WorkflowStage::where('code', 'TRAFFIC')->first();

    $jobNumber = 'JOB-' . now()->format('Y') . '-' . str_pad(
        (CreativeJob::count() + 1),
        5,
        '0',
        STR_PAD_LEFT
    );

    $job = CreativeJob::create([
        'job_number' => $jobNumber,
        'client_id' => $validated['client_id'],
        'project_id' => $validated['project_id'] ?? null,
        'job_category_id' => $validated['job_category_id'] ?? null,
        'current_workflow_stage_id' => $trafficStage?->id,
        'title' => $validated['title'],
        'brief' => $validated['brief'] ?? null,
        'priority' => $validated['priority'],
        'received_at' => now(),
        'first_draft_due_at' => $validated['first_draft_due_at'] ?? null,
        'final_due_at' => $validated['final_due_at'] ?? null,
        'estimated_hours' => $validated['estimated_hours'] ?? 0,
        'created_by' => Auth::id(),
    ]);

    return redirect()
        ->route('jobs.show', $job)
        ->with('success', 'Job created successfully.');
}

    public function show(string $id)
{
    $job = CreativeJob::with([
        'client',
        'project',
        'category',
        'currentWorkflowStage',
    ])->findOrFail($id);

    return view('jobs.show', compact('job'));
}

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}