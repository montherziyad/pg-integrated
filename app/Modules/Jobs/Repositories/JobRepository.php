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
        return $this->model
            ->newQuery()
            ->visibleToUser($user)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('job_number', 'ilike', "%{$search}%")
                        ->orWhere('title', 'ilike', "%{$search}%")
                        ->orWhere('priority', 'ilike', "%{$search}%")
                        ->orWhere('employee_handover_status', 'ilike', "%{$search}%")
                        ->orWhereHas('client', fn ($client) => $client->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('project', fn ($project) => $project->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('responsibleUser', fn ($user) => $user->where('name', 'ilike', "%{$search}%")->orWhere('email', 'ilike', "%{$search}%"))
                        ->orWhereHas('assignments.assignee', fn ($user) => $user->where('name', 'ilike', "%{$search}%")->orWhere('email', 'ilike', "%{$search}%"));
                });
            })
            ->with([
                'client.accountManager',
                'client.clientServiceUsers',
                'project',
                'category',
                'currentWorkflowStage',
                'responsibleUser',
                'assignments.assignee',
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
                'activities.user',
                'assets.uploader',
                'employeeHandoverSubmitter',
            ])
            ->find($id);
    }
}