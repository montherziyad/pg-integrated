<?php

namespace App\Modules\Categories\Services;

use App\Models\JobCategory;
use App\Modules\Categories\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(protected CategoryRepository $repository) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?JobCategory
    {
        return $this->repository->find($id);
    }

    public function create(array $data): JobCategory
    {
        $data['is_active'] ??= true;

        return $this->repository->create($data);
    }

    public function update(JobCategory $category, array $data): bool
    {
        $data['is_active'] ??= false;

        return $this->repository->update($category, $data);
    }

    public function delete(JobCategory $category): bool
    {
        return $this->repository->delete($category);
    }
}
