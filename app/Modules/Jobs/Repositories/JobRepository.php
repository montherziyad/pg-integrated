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
                'client',
                'project',
                'category',
                'currentWorkflowStage',
            ])
            ->latest()
            ->get();
    }

    public function find(int $id)
    {
        return $this->model
            ->newQuery()
            ->with([
                'client',
                'project',
                'category',
                'currentWorkflowStage',
                'assignments.team',
                'assignments.supervisor',
                'assignments.assignee',
                'activities.user',
                'assets.uploader',
            ])
            ->find($id);
    }
}