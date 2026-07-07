<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Modules\Users\Requests\StoreRoleRequest;
use App\Modules\Users\Requests\UpdateRoleRequest;
use App\Modules\Users\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $columns = ['name','code','description'];

        $roles = Role::query()
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

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create', [
            'role' => new Role,
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());

        return redirect()->route('admin.roles.show', $role)->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role = $this->roleService->find($role->id);

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->update($role, $request->validated());

        return redirect()->route('admin.roles.show', $role)->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->roleService->delete($role);

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
