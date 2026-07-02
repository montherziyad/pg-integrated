<?php

namespace App\Modules\Projects\Services;

use App\Models\Project;
use App\Modules\Projects\Repositories\ProjectRepository;

class ProjectService
{
    public function __construct(protected ProjectRepository $repository) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Project
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Project
    {
        $data['is_active'] ??= true;

        return $this->repository->create($data);
    }

    public function update(Project $project, array $data): bool
    {
        $data['is_active'] ??= false;

        return $this->repository->update($project, $data);
    }

    public function delete(Project $project): bool
    {
        return $this->repository->delete($project);
    }
}
