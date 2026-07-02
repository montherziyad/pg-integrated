<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Modules\Categories\Requests\StoreCategoryRequest;
use App\Modules\Categories\Requests\UpdateCategoryRequest;
use App\Modules\Categories\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service) {}

    public function index()
    {
        return view('admin.categories.index', ['categories' => $this->service->all()]);
    }

    public function create()
    {
        return view('admin.categories.create', ['category' => new JobCategory]);
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
        return view('admin.categories.edit', compact('category'));
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
