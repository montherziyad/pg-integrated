<?php

namespace App\Modules\Traffic\Repositories;

use App\Models\WorkflowStage;

class TrafficRepository
{
    public function board($user = null)
    {
        $user = $user ?: auth()->user();

        // Determine if user is a manager/admin (same list as job repository)
        $roleCode = strtoupper($user?->role?->code ?? '');
        $managerRoles = ['SUPER_ADMIN', 'GENERAL_MANAGER', 'OPERATIONS_MANAGER', 'TRAFFIC_MANAGER', 'ACCOUNT_MANAGER', 'CLIENT_SERVICE_MANAGER', 'HR'];
        $restrictToAssignments = ! in_array($roleCode, $managerRoles, true);

        // Fetch regular stages with their jobs
        $stages = WorkflowStage::query()
            ->where('is_active', true)
            ->with(['jobs' => function ($query) use ($restrictToAssignments, $user) {
                $query->where('is_archived', false)
                    ->with(['client', 'project', 'assignments.assignee'])
                    ->orderByRaw('final_due_at IS NULL')
                    ->orderBy('final_due_at');

                if ($restrictToAssignments && $user) {
                    $query->where(function ($q) use ($user) {
                        $q->where('responsible_user_id', $user->id)
                            ->orWhereHas('assignments', fn ($a) => $a->where('user_id', $user->id)->orWhere('supervisor_id', $user->id));
                    });
                }
            }])
            ->orderBy('sort_order')
            ->get();

        // Also include any jobs that currently have no stage (unstaged)
        $unstagedJobsQuery = \App\Models\CreativeJob::query()
            ->whereNull('current_workflow_stage_id')
            ->where('is_archived', false)
            ->with(['client', 'project', 'assignments.assignee'])
            ->orderByRaw('final_due_at IS NULL')
            ->orderBy('final_due_at');

        if ($restrictToAssignments && $user) {
            $unstagedJobsQuery->where(function ($q) use ($user) {
                $q->where('responsible_user_id', $user->id)
                    ->orWhereHas('assignments', fn ($a) => $a->where('user_id', $user->id)->orWhere('supervisor_id', $user->id));
            });
        }

        $unstagedJobs = $unstagedJobsQuery->get();

        if ($unstagedJobs->isNotEmpty()) {
            $virtual = new \Illuminate\Support\Collection([ (object) [
                'id' => 0,
                'code' => 'unstaged',
                'name' => 'Unstaged / No Stage',
                'color' => '#9CA3AF',
                'jobs' => $unstagedJobs,
            ]]);

            // Prepend virtual stage so unstaged jobs appear first
            return $virtual->merge($stages);
        }

        return $stages;
    }
}
