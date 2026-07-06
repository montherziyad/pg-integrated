<?php

namespace App\Modules\Projects\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function all()
    {
        return Project::with(['client.accountManager', 'client.clientServiceUsers', 'projectManager'])->orderBy('name')->get();
    }

    public function find(int $id): ?Project
    {
        return Project::with(['client.accountManager', 'client.clientServiceUsers', 'projectManager'])->find($id);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data): bool
    {
        return $project->update($data);
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }
}
