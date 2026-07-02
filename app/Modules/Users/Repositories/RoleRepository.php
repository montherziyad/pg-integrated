<?php

namespace App\Modules\Users\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function all()
    {
        return Role::withCount('users')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?Role
    {
        return Role::with('users')->find($id);
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
