<x-app-layout>
    <x-slot name="header">Branch Details</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $branch->name }}</h2>
                <p class="pg-subtitle">{{ $branch->code }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.branches.edit', $branch->id) }}" class="pg-btn-primary">
                    Edit
                </a>

                <a href="{{ route('admin.branches.index') }}" class="pg-btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="pg-stat-label">Country</div>
                    <div class="font-semibold">{{ $branch->country ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">City</div>
                    <div class="font-semibold">{{ $branch->city ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Status</div>
                    <div class="font-semibold">
                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-lg font-bold mb-4">Branch Users</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-slate-500">
                                <th class="py-3">Name</th>
                                <th>Email</th>
                                <th>Team</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($branch->users as $user)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-3">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->team?->name ?? '-' }}</td>
                                    <td>{{ $user->role?->name ?? '-' }}</td>
                                    <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500">
                                        No users assigned to this branch.
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
