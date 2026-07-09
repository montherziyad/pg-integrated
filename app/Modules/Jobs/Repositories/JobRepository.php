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

        if ($user) {
            $roleCode = strtoupper($user->role?->code ?? '');
            $jobTitle = strtolower((string) $user->job_title);
            $managerRoles = ['SUPER_ADMIN', 'GENERAL_MANAGER', 'OPERATIONS_MANAGER', 'TRAFFIC_MANAGER', 'HR'];

            $canSeeAll = in_array($roleCode, $managerRoles, true);
            $isClientService = str_contains($roleCode, 'CLIENT_SERVICE')
                || str_contains($roleCode, 'ACCOUNT')
                || str_contains($jobTitle, 'client service')
                || str_contains($jobTitle, 'account manager');

            if ($isClientService && ! $canSeeAll) {
                $query->where(function ($q) use ($user) {
                    $q->where('responsible_user_id', $user->id)
                        ->orWhereHas('client.clientServiceUsers', fn ($clientService) => $clientService->where('users.id', $user->id));
                });
            } elseif (! $canSeeAll) {
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
