<x-app-layout>
    <x-slot name="header">
        Users Management
    </x-slot>

    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Users Management</h2>
                <p class="pg-subtitle mt-1">
                    Manage company users, teams, roles and access.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="pg-btn-primary">
                + New User
            </a>
        </div>

        @include('admin.partials.nav')

        @include('admin.partials.search', ['action' => route('admin.users.index'), 'placeholder' => 'Search users by name, email, phone, branch, team, or role...'])

        <div class="pg-card">
            <div class="pg-card-body">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-slate-500">
                                <th class="py-3">Name</th>
                                <th>Email</th>
                                <th>Branch</th>
                                <th>Team</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $user)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-4 font-semibold">
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                        {{ $user->email }}
                                    </td>

                                    <td>
                                        {{ $user->branch?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $user->team?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $user->role?->name ?? '-' }}
                                    </td>

                                    <td>
                                        @if($user->is_active)
                                            <span class="pg-badge pg-badge-completed">
                                                Active
                                            </span>
                                        @else
                                            <span class="pg-badge pg-badge-review">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="pg-btn-secondary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-slate-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>