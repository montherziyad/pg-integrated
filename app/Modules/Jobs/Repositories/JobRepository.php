<?php

namespace App\Modules\Jobs\Repositories;

use App\Models\CreativeJob;
use Illuminate\Database\Eloquent\Collection;

class JobRepository
{
    public function all(): Collection
    {
        return CreativeJob::with([
            'client',
            'project',
            'category',
            'currentWorkflowStage',
        ])->latest()->get();
    }

    public function find(int $id): ?CreativeJob
    {
        return CreativeJob::with([
            'client',
            'project',
            'category',
            'currentWorkflowStage',
        ])->find($id);
    }

    public function create(array $data): CreativeJob
    {
        return CreativeJob::create($data);
    }

    public function update(CreativeJob $job, array $data): bool
    {
        return $job->update($data);
    }

    public function delete(CreativeJob $job): bool
    {
        return $job->delete();
    }
}