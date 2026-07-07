<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Modules\Users\Requests\StoreUserRequest;
use App\Modules\Users\Requests\UpdateUserRequest;
use App\Modules\Users\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->with(['branch', 'team', 'role'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('phone', 'ilike', "%{$search}%")
                        ->orWhereHas('branch', fn ($branch) => $branch->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('team', fn ($team) => $team->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('role', fn ($role) => $role->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', [
            'branches' => Branch::orderBy('name')->get(),
            'teams' => Team::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
            'user' => new User(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return redirect()->route('admin.users.show', $user)->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user = $this->userService->find($user->id);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'branches' => Branch::orderBy('name')->get(),
            'teams' => Team::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
