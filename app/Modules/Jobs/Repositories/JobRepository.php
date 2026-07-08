<?php

namespace App\Modules\Jobs\Repositories;

use App\Core\Repositories\BaseRepository;
use App\Models\CreativeJob;

class JobRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new CreativeJob();
    }

    public function all($user = null, string $search = '')
    {
        $query = $this->model
            ->newQuery()
            ->visibleToUser($user);

        // If user is provided and does NOT have broad visibility permissions,
        // restrict to jobs where the user is assigned or responsible.
        if ($user) {
            $canSeeAll = $user->canAccessScreen('traffic_board')
                || $user->canAccessScreen('email_intake')
                || $user->canAccessScreen('deliveries')
                || $user->canAccessScreen('clients')
                || $user->canAccessScreen('team_workload');

            if (! $canSeeAll) {
                $query->where(function ($q) use ($user) {
                    $q->where('responsible_user_id', $user->id)
                        ->orWhereHas('assignments', fn ($a) => $a->where('user_id', $user->id)->orWhere('supervisor_id', $user->id));
                });
            }
        }

        $query->when($search !== '', function ($q) use ($search): void {
            $q->where(function ($q) use ($search): void {
                $q
                    ->where('job_number', 'ilike', "%{$search}%")
                    ->orWhere('title', 'ilike', "%{$search}%")
                    ->orWhere('priority', 'ilike', "%{$search}%")
                    ->orWhere('employee_handover_status', 'ilike', "%{$search}%")
                    ->orWhereHas('client', fn ($client) => $client->where('name', 'ilike', "%{$search}%"))
                    ->orWhereHas('project', fn ($project) => $project->where('name', 'ilike', "%{$search}%"))
                    ->orWhereHas('responsibleUser', fn ($user) => $user->where('name', 'ilike', "%{$search}%")->orWhere('email', 'ilike', "%{$search}%"))
                    ->orWhereHas('assignments.assignee', fn ($user) => $user->where('name', 'ilike', "%{$search}%")->orWhere('email', 'ilike', "%{$search}%"))
                    ->orWhereHas('assignments.supervisor', fn ($user) => $user->where('name', 'ilike', "%{$search}%")->orWhere('email', 'ilike', "%{$search}%"));
            });
        });

        return $query->with([
                'client.accountManager',
                'client.clientServiceUsers',
                'project',
                'category',
                'currentWorkflowStage',
                'responsibleUser',
                'assignments.assignee',
                'assignments.supervisor',
                'productionDueConfirmer',
                'employeeHandoverSubmitter',
            ])
            ->latest()
            ->get();
    }

    public function find(int $id)
    {
        return $this->model
            ->newQuery()
            ->with([
                'client.accountManager',
                'client.clientServiceUsers',
                'project',
                'category',
                'currentWorkflowStage',
                'responsibleUser',
                'assignments.team',
                'assignments.supervisor',
                'assignments.assignee',
                'productionDueConfirmer',
                'activities.user',
                'assets.uploader',
                'employeeHandoverSubmitter',
            ])
            ->find($id);
    }
}
