<x-app-layout>
    <x-slot name="header">User Details</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $user->name }}</h2>
                <p class="pg-subtitle">{{ $user->email }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="pg-btn-primary">
                    Edit
                </a>

                <a href="{{ route('admin.users.index') }}" class="pg-btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="pg-stat-label">Branch</div>
                    <div class="font-semibold">{{ $user->branch?->name ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Team</div>
                    <div class="font-semibold">{{ $user->team?->name ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Role</div>
                    <div class="font-semibold">{{ $user->role?->name ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Job Title</div>
                    <div class="font-semibold">{{ $user->job_title ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Mobile</div>
                    <div class="font-semibold">{{ $user->mobile ?? '-' }}</div>
                </div>

                <div>
                    <div class="pg-stat-label">Capacity Hours</div>
                    <div class="font-semibold">{{ $user->capacity_hours }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>