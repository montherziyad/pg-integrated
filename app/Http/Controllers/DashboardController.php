<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientProjectRequest;
use App\Models\CmsPage;
use App\Models\CreativeJob;
use App\Models\EmailIntake;
use App\Models\EmployeeLeave;
use App\Models\EmployeeNotification;
use App\Models\JobActivity;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkflowStage;
use App\Services\EventCalendarService;
use App\Modules\Workload\Services\WorkloadService;

class DashboardController extends Controller
{
    public function __construct(
        protected WorkloadService $workloadService,
        protected EventCalendarService $eventCalendarService
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
        $pendingLeaveRequests = EmployeeLeave::where('status', 'pending')->count();
        $employeesOnLeaveToday = EmployeeLeave::where('status', 'approved')
            ->whereDate('starts_at', '<=', now()->toDateString())
            ->whereDate('ends_at', '>=', now()->toDateString())
            ->count();

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
            'responsibleUser',
            'assignments.assignee',
        ])
            ->visibleToUser(auth()->user())
            ->latest()
            ->take(10)
            ->get();

        $myAssignedJobs = CreativeJob::query()
            ->with(['client', 'project', 'currentWorkflowStage', 'responsibleUser', 'assignments.assignee', 'employeeHandoverSubmitter'])
            ->where(function ($query): void {
                $query->where('responsible_user_id', auth()->id())
                    ->orWhereHas('assignments', fn ($assignment) => $assignment
                        ->where('user_id', auth()->id())
                        ->orWhere('supervisor_id', auth()->id()));
            })
            ->where('is_archived', false)
            ->latest()
            ->take(8)
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

        $employeeNotifications = EmployeeNotification::query()
            ->with('job')
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        $saudiCalendarEvents = $this->eventCalendarService->upcoming('employee', 'Saudi Arabia', 6, auth()->user()?->team_id)->all();

        return view('dashboard', compact(
            'totalJobs',
            'urgentJobs',
            'totalClients',
            'totalProjects',
            'pendingEmailIntakes',
            'pendingClientApprovals',
            'pendingEmployeeApprovals',
            'newClientRequests',
            'pendingLeaveRequests',
            'employeesOnLeaveToday',
            'pendingClients',
            'pendingEmployees',
            'latestClientRequests',
            'websitePages',
            'latestJobs',
            'myAssignedJobs',
            'latestActivities',
            'workflowStages',
            'workloadUsers',
            'employeeNotifications',
            'saudiCalendarEvents'
        ));
    }

}
