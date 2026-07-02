<?php

namespace App\Modules\Users\Services;

use App\Models\Branch;
use App\Modules\Users\Repositories\BranchRepository;

class BranchService
{
    public function __construct(
        protected BranchRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Branch
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Branch
    {
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->repository->create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        $data['is_active'] = $data['is_active'] ?? false;

        return $this->repository->update($branch, $data);
    }

    public function delete(Branch $branch): bool
    {
        return $this->repository->delete($branch);
    }
}
