<?php

namespace App\Modules\Users\Repositories;

use App\Models\Team;

class TeamRepository
{
    public function all()
    {
        return Team::withCount('users')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?Team
    {
        return Team::with('users')->find($id);
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function update(Team $team, array $data): bool
    {
        return $team->update($data);
    }

    public function delete(Team $team): bool
    {
        return $team->delete();
    }
}
