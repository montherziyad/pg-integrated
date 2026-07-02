<?php

namespace App\Modules\Categories\Repositories;

use App\Models\JobCategory;

class CategoryRepository
{
    public function all()
    {
        return JobCategory::withCount('jobs')->orderBy('name')->get();
    }

    public function find(int $id): ?JobCategory
    {
        return JobCategory::with(['jobs' => fn ($query) => $query->with(['client', 'currentWorkflowStage'])->latest()])->find($id);
    }

    public function create(array $data): JobCategory
    {
        return JobCategory::create($data);
    }

    public function update(JobCategory $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(JobCategory $category): bool
    {
        return $category->delete();
    }
}
