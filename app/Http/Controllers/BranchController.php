<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Modules\Users\Requests\StoreBranchRequest;
use App\Modules\Users\Requests\UpdateBranchRequest;
use App\Modules\Users\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(
        protected BranchService $branchService
    ) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $columns = ['name','code','city','country'];

        $branches = Branch::query()
            ->withCount('users')
            ->when($search !== '', function ($query) use ($search, $columns) {
                $query->where(function ($query) use ($search, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'ilike', "%{$search}%");
                    }
                });
            })
            ->orderBy('name')
            ->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create', [
            'branch' => new Branch,
        ]);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->create($request->validated());

        return redirect()->route('admin.branches.show', $branch)->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $branch = $this->branchService->find($branch->id);

        return view('admin.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->branchService->update($branch, $request->validated());

        return redirect()->route('admin.branches.show', $branch)->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $this->branchService->delete($branch);

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
