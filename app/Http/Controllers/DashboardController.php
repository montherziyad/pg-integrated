<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\EmailIntake;
use App\Models\JobActivity;
use App\Models\Project;
use App\Models\WorkflowStage;
use App\Modules\Workload\Services\WorkloadService;

class DashboardController extends Controller
{
    public function __construct(
        protected WorkloadService $workloadService
    ) {}

    public function index()
    {
        $totalJobs = CreativeJob::count();

        $urgentJobs = CreativeJob::whereIn('priority', [
            'URGENT',
            'CRITICAL',
        ])->count();

        $totalClients = Client::count();

        $totalProjects = Project::count();

        $pendingEmailIntakes = EmailIntake::where('status', 'NEW')->count();

        $latestJobs = CreativeJob::with([
            'client',
            'project',
            'currentWorkflowStage',
        ])
            ->latest()
            ->take(10)
            ->get();

        $latestActivities = JobActivity::with([
            'job',
            'user',
        ])
            ->latest('activity_at')
            ->take(10)
            ->get();

        $workflowStages = WorkflowStage::withCount('jobs')
            ->orderBy('sort_order')
            ->get();

        $workloadUsers = $this->workloadService->users()->take(5);

        return view('dashboard', compact(
            'totalJobs',
            'urgentJobs',
            'totalClients',
            'totalProjects',
            'pendingEmailIntakes',
            'latestJobs',
            'latestActivities',
            'workflowStages',
            'workloadUsers'
        ));
    }
}
