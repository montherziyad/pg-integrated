<?php

namespace App\Modules\Users\Services;

use App\Models\Team;
use App\Modules\Users\Repositories\TeamRepository;

class TeamService
{
    public function __construct(
        protected TeamRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Team
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Team
    {
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->repository->create($data);
    }

    public function update(Team $team, array $data): bool
    {
        $data['is_active'] = $data['is_active'] ?? false;

        return $this->repository->update($team, $data);
    }

    public function delete(Team $team): bool
    {
        return $this->repository->delete($team);
    }
}
