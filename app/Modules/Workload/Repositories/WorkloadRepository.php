<?php

namespace App\Modules\Workload\Repositories;

use App\Models\User;

class WorkloadRepository
{
    public function users()
    {
        return User::query()
            ->where('is_active', true)
            ->with(['team', 'role', 'employeeLeaves' => fn ($query) => $query->where('status', 'approved')->whereDate('starts_at', '<=', now()->toDateString())->whereDate('ends_at', '>=', now()->toDateString())])
            ->withCount(['jobAssignments as active_jobs_count' => fn ($query) => $query
                ->whereIn('status', ['ASSIGNED', 'IN_PROGRESS', 'ON_HOLD'])])
            ->withSum(['jobAssignments as assigned_hours' => fn ($query) => $query
                ->whereIn('status', ['ASSIGNED', 'IN_PROGRESS', 'ON_HOLD'])], 'estimated_hours')
            ->orderBy('name')
            ->get();
    }
}
