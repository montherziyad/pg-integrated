<x-app-layout>
    <x-slot name="header">Team Details</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $team->name }}</h2>
                <p class="pg-subtitle">{{ $team->code }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.teams.edit', $team->id) }}" class="pg-btn-primary">
                    Edit
                </a>

                <a href="{{ route('admin.teams.index') }}" class="pg-btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body space-y-4">
                <div>
                    <div class="pg-stat-label">Description</div>
                    <div class="font-semibold">{{ $team->description ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Status</div>
                    <div class="font-semibold">
                        {{ $team->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-lg font-bold mb-4">Team Members</h3>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($team->users as $user)
                            <tr class="border-b last:border-b-0">
                                <td class="py-3">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-500">
                                    No members in this team.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>