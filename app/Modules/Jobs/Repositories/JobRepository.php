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

    public function all()
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
            ])
            ->find($id);
    }
}