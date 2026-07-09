<?php

namespace App\Modules\Traffic\Repositories;

use App\Models\CreativeJob;
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

        $baseQuery = fn () => CreativeJob::query()
            ->where('is_archived', false)
            ->where(function ($query): void {
                $query->whereNull('delivery_review_status')
                    ->orWhere('delivery_review_status', '!=', 'published');
            })
            ->with(['client', 'project', 'assignments.assignee', 'assignments.supervisor', 'productionDueConfirmer'])
            ->tap($visibleToUser)
            ->orderByRaw('final_due_at IS NULL')
            ->orderBy('final_due_at')
            ->latest();

        $columns = [
            [
                'id' => 1,
                'code' => 'traffic_review',
                'name' => '1. Traffic Review',
                'description' => 'New jobs and briefs that Traffic must review, validate, and assign.',
                'action' => 'Review brief, client, project, priority, due date, then assign team.',
                'jobs' => $baseQuery()
                    ->where(function ($query): void {
                        $query->whereDoesntHave('assignments')
                            ->orWhereNull('current_workflow_stage_id');
                    })
                    ->where(function ($query): void {
                        $query->whereNull('employee_handover_status')
                            ->orWhere('employee_handover_status', 'not_submitted');
                    })
                    ->whereNull('production_due_at')
                    ->get(),
            ],
            [
                'id' => 2,
                'code' => 'waiting_team_date',
                'name' => '2. Waiting Team Date',
                'description' => 'Assigned jobs waiting for the designer or lead to confirm delivery date.',
                'action' => 'Follow up with assigned team to confirm expected delivery to Traffic.',
                'jobs' => $baseQuery()
                    ->whereNull('production_due_at')
                    ->where(function ($query): void {
                        $query->whereHas('assignments')
                            ->orWhereNotNull('responsible_user_id');
                    })
                    ->where(function ($query): void {
                        $query->whereNull('employee_handover_status')
                            ->orWhere('employee_handover_status', 'not_submitted');
                    })
                    ->get(),
            ],
            [
                'id' => 3,
                'code' => 'production',
                'name' => '3. In Production',
                'description' => 'Team confirmed a date and is working before handover.',
                'action' => 'Monitor delivery date. Team must upload handover link/files when ready.',
                'jobs' => $baseQuery()
                    ->whereNotNull('production_due_at')
                    ->where(function ($query): void {
                        $query->whereNull('employee_handover_status')
                            ->orWhere('employee_handover_status', 'not_submitted');
                    })
                    ->where(function ($query): void {
                        $query->whereNull('delivery_review_status')
                            ->orWhere('delivery_review_status', 'draft');
                    })
                    ->get(),
            ],
            [
                'id' => 4,
                'code' => 'handover_review',
                'name' => '4. Handover Review',
                'description' => 'Designer submitted files/link. Traffic must review and send to Client Service.',
                'action' => 'Open handover, view the link/files, then approve or request revision.',
                'jobs' => $baseQuery()
                    ->where('employee_handover_status', 'submitted_to_traffic')
                    ->where(function ($query): void {
                        $query->whereNull('delivery_review_status')
                            ->orWhere('delivery_review_status', 'draft');
                    })
                    ->get(),
            ],
            [
                'id' => 5,
                'code' => 'client_service_review',
                'name' => '5. Client Service Review',
                'description' => 'Traffic checked the output. Client Service must approve or request revision.',
                'action' => 'Client Service approves and publishes to client portal, or sends revision notes back.',
                'jobs' => $baseQuery()
                    ->where('delivery_review_status', 'checked')
                    ->get(),
            ],
        ];

        return collect($columns)->map(fn (array $column) => (object) $column);
    }
}
