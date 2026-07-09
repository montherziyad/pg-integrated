<?php

namespace App\Modules\Traffic\Repositories;

use App\Models\CreativeJob;
use App\Models\WorkflowStage;
use Illuminate\Support\Collection;

class TrafficRepository
{
    public function board($user = null)
    {
        $user = $user ?: auth()->user();

        // Determine if user is a manager/admin (same list as job repository)
        $roleCode = strtoupper($user?->role?->code ?? '');
        $managerRoles = ['SUPER_ADMIN', 'GENERAL_MANAGER', 'OPERATIONS_MANAGER', 'TRAFFIC_MANAGER', 'ACCOUNT_MANAGER', 'CLIENT_SERVICE_MANAGER', 'HR'];
        $restrictToAssignments = ! in_array($roleCode, $managerRoles, true);

        $visibleToUser = function ($query) use ($restrictToAssignments, $user): void {
            if ($restrictToAssignments && $user) {
                $query->where(function ($q) use ($user) {
                    $q->where('responsible_user_id', $user->id)
                        ->orWhereHas('assignments', fn ($a) => $a->where('user_id', $user->id)->orWhere('supervisor_id', $user->id));
                });
            }
        };

        $submittedToTrafficJobs = CreativeJob::query()
            ->where('is_archived', false)
            ->where('employee_handover_status', 'submitted_to_traffic')
            ->whereNotIn('delivery_review_status', ['checked', 'published'])
            ->with(['client', 'project', 'assignments.assignee', 'assignments.supervisor', 'productionDueConfirmer'])
            ->tap($visibleToUser)
            ->orderByRaw('final_due_at IS NULL')
            ->orderBy('final_due_at')
            ->get();

        $waitingTeamDateJobs = CreativeJob::query()
            ->where('is_archived', false)
            ->whereNull('production_due_at')
            ->where(function ($query): void {
                $query->whereHas('assignments')
                    ->orWhereNotNull('responsible_user_id');
            })
            ->where(function ($query): void {
                $query->whereNull('employee_handover_status')
                    ->orWhere('employee_handover_status', 'not_submitted');
            })
            ->with(['client', 'project', 'assignments.assignee', 'assignments.supervisor', 'productionDueConfirmer'])
            ->tap($visibleToUser)
            ->orderByRaw('final_due_at IS NULL')
            ->orderBy('final_due_at')
            ->get();

        $virtualStages = new Collection();

        if ($submittedToTrafficJobs->isNotEmpty()) {
            $virtualStages->push((object) [
                'id' => -2,
                'code' => 'submitted_to_traffic',
                'name' => 'Submitted to Traffic',
                'description' => 'Traffic must review handover files and send checked output to Client Service.',
                'jobs' => $submittedToTrafficJobs,
            ]);
        }

        if ($waitingTeamDateJobs->isNotEmpty()) {
            $virtualStages->push((object) [
                'id' => -1,
                'code' => 'waiting_team_date',
                'name' => 'Waiting Team Date',
                'description' => 'Assigned team or lead must confirm expected delivery time.',
                'jobs' => $waitingTeamDateJobs,
            ]);
        }

        // Fetch regular stages with their jobs
        $stages = WorkflowStage::query()
            ->where('is_active', true)
            ->with(['jobs' => function ($query) use ($visibleToUser) {
                $query->where('is_archived', false)
                    ->where(function ($query): void {
                        $query->where('employee_handover_status', '!=', 'submitted_to_traffic')
                            ->orWhereNull('employee_handover_status')
                            ->orWhereIn('delivery_review_status', ['checked', 'published']);
                    })
                    ->with(['client', 'project', 'assignments.assignee', 'assignments.supervisor', 'productionDueConfirmer'])
                    ->tap($visibleToUser)
                    ->orderByRaw('final_due_at IS NULL')
                    ->orderBy('final_due_at');
            }])
            ->orderBy('sort_order')
            ->get();

        // Also include any jobs that currently have no stage (unstaged)
        $unstagedJobsQuery = CreativeJob::query()
            ->whereNull('current_workflow_stage_id')
            ->where('is_archived', false)
            ->where(function ($query): void {
                $query->where('employee_handover_status', '!=', 'submitted_to_traffic')
                    ->orWhereNull('employee_handover_status');
            })
            ->with(['client', 'project', 'assignments.assignee', 'assignments.supervisor', 'productionDueConfirmer'])
            ->tap($visibleToUser)
            ->orderByRaw('final_due_at IS NULL')
            ->orderBy('final_due_at');

        $unstagedJobs = $unstagedJobsQuery->get();

        if ($unstagedJobs->isNotEmpty()) {
            $virtualStages->push((object) [
                'id' => 0,
                'code' => 'unstaged',
                'name' => 'Unstaged / No Stage',
                'description' => 'These jobs need a workflow stage.',
                'jobs' => $unstagedJobs,
            ]);
        }

        return $virtualStages->merge($stages);
    }
}
