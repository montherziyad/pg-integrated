<?php

namespace App\Modules\Categories\Repositories;

use App\Models\JobCategory;

class CategoryRepository
{
    public function all()
    {
        return JobCategory::with('parent')
            ->withCount(['jobs', 'descendantJobs'])
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?JobCategory
    {
        return JobCategory::with([
            'parent',
            'children',
            'jobs' => fn ($query) => $query->with(['client', 'currentWorkflowStage'])->latest(),
        ])->find($id);
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
