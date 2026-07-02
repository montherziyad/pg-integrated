<?php

namespace App\Modules\Users\Repositories;

use App\Models\Branch;

class BranchRepository
{
    public function all()
    {
        return Branch::withCount('users')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?Branch
    {
        return Branch::with('users')->find($id);
    }

    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        return $branch->update($data);
    }

    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }
}
