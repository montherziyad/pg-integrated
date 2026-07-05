<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CmsPage;
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

        $websitePages = CmsPage::query()
            ->orderByRaw("case key when 'home' then 1 when 'about' then 2 when 'services' then 3 when 'work' then 4 when 'team' then 5 when 'clients' then 6 when 'contact' then 7 else 99 end")
            ->get(['id', 'key', 'title', 'slug', 'is_published', 'sections']);

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
            'websitePages',
            'latestJobs',
            'latestActivities',
            'workflowStages',
            'workloadUsers'
        ));
    }
}
