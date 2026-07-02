<?php

namespace App\Modules\Users\Services;

use App\Models\Role;
use App\Modules\Users\Repositories\RoleRepository;

class RoleService
{
    public function __construct(
        protected RoleRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Role
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Role
    {
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->repository->create($data);
    }

    public function update(Role $role, array $data): bool
    {
        $data['is_active'] = $data['is_active'] ?? false;

        return $this->repository->update($role, $data);
    }

    public function delete(Role $role): bool
    {
        return $this->repository->delete($role);
    }
}
