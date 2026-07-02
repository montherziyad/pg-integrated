<?php

namespace App\Modules\Reports\Repositories;

use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\JobAssignment;
use App\Models\User;

class ReportRepository
{
    public function summary(): array
    {
        return [
            'totalJobs' => CreativeJob::count(),
            'activeJobs' => CreativeJob::where('is_archived', false)->count(),
            'archivedJobs' => CreativeJob::where('is_archived', true)->count(),
            'overdueJobs' => CreativeJob::where('is_archived', false)->whereNotNull('final_due_at')->where('final_due_at', '<', now())->count(),
            'estimatedHours' => (float) CreativeJob::sum('estimated_hours'),
            'actualHours' => (float) CreativeJob::sum('actual_hours'),
            'clients' => Client::withCount('projects')->orderByDesc('projects_count')->take(10)->get(),
            'team' => User::withCount(['jobAssignments as completed_jobs_count' => fn ($query) => $query->where('status', 'COMPLETED')])
                ->withSum('jobAssignments as actual_hours', 'actual_hours')->orderByDesc('completed_jobs_count')->take(10)->get(),
            'assignmentStatus' => JobAssignment::selectRaw('status, count(*) as total')->groupBy('status')->orderBy('status')->get(),
        ];
    }
}
