<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientProjectRequest;
use App\Models\CmsPage;
use App\Models\CreativeJob;
use App\Models\EmailIntake;
use App\Models\JobActivity;
use App\Models\Project;
use App\Models\User;
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

        $pendingEmailIntakes = EmailIntake::where('status', 'NEW')
            ->where('validation_passed', true)
            ->count();
        $pendingClientApprovals = Client::where('is_active', false)->orWhere('portal_enabled', false)->count();
        $pendingEmployeeApprovals = User::where('is_active', false)->count();
        $newClientRequests = ClientProjectRequest::where('status', 'new')->count();

        $pendingClients = Client::with('accountManager')
            ->where('is_active', false)
            ->orWhere('portal_enabled', false)
            ->latest()
            ->take(5)
            ->get();

        $pendingEmployees = User::with(['role', 'team'])
            ->where('is_active', false)
            ->latest()
            ->take(5)
            ->get();

        $latestClientRequests = ClientProjectRequest::with(['client', 'project'])
            ->where('status', 'new')
            ->latest()
            ->take(5)
            ->get();

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
            'pendingClientApprovals',
            'pendingEmployeeApprovals',
            'newClientRequests',
            'pendingClients',
            'pendingEmployees',
            'latestClientRequests',
            'websitePages',
            'latestJobs',
            'latestActivities',
            'workflowStages',
            'workloadUsers'
        ));
    }
}
