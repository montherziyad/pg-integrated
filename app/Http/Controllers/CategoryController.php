<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Modules\Categories\Requests\StoreCategoryRequest;
use App\Modules\Categories\Requests\UpdateCategoryRequest;
use App\Modules\Categories\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $categories = JobCategory::query()
            ->with('parent')
            ->withCount(['jobs', 'descendantJobs'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('code', 'ilike', "%{$search}%")
                        ->orWhere('default_team', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%")
                        ->orWhereHas('parent', fn ($parent) => $parent->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create', [
            'category' => new JobCategory,
            'parentCategories' => JobCategory::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create($request->validated());

        return redirect()->route('admin.categories.show', $category)->with('success', 'Category created successfully.');
    }

    public function show(JobCategory $category)
    {
        return view('admin.categories.show', ['category' => $this->service->find($category->id)]);
    }

    public function edit(JobCategory $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'parentCategories' => JobCategory::whereNull('parent_id')
                ->whereKeyNot($category->id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateCategoryRequest $request, JobCategory $category)
    {
        $this->service->update($category, $request->validated());

        return redirect()->route('admin.categories.show', $category)->with('success', 'Category updated successfully.');
    }

    public function destroy(JobCategory $category)
    {
        $this->service->delete($category);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
