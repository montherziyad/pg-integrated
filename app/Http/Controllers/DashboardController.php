<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\JobActivity;
use App\Models\Project;
use App\Models\WorkflowStage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJobs = CreativeJob::count();

        $urgentJobs = CreativeJob::whereIn('priority', [
            'URGENT',
            'CRITICAL',
        ])->count();

        $totalClients = Client::count();

        $totalProjects = Project::count();

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

        return view('dashboard', compact(
            'totalJobs',
            'urgentJobs',
            'totalClients',
            'totalProjects',
            'latestJobs',
            'latestActivities',
            'workflowStages'
        ));
    }
}