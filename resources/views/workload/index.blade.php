<x-app-layout>
    <x-slot name="header">Team Workload</x-slot>
    <div class="space-y-6">
        <div><h2 class="pg-title">Team Workload</h2><p class="pg-subtitle mt-1">Current assignments compared with employee capacity.</p></div>
        <div class="pg-card"><div class="pg-card-body overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Employee</th><th>Team</th><th>Role</th><th>Active Jobs</th><th>Assigned Hours</th><th>Capacity</th><th>Utilization</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                    @php($utilization = $user->capacity_hours > 0 ? round(((float) $user->assigned_hours / $user->capacity_hours) * 100) : 0)
                    <tr class="border-b last:border-b-0">
                        <td class="py-4 font-semibold">{{ $user->name }}</td><td>{{ $user->team?->name ?? '-' }}</td><td>{{ $user->role?->name ?? '-' }}</td>
                        <td>{{ $user->active_jobs_count }}</td><td>{{ number_format((float) $user->assigned_hours, 1) }}</td><td>{{ $user->capacity_hours }}</td><td>{{ $utilization }}%</td>
                        <td><span class="pg-badge {{ $utilization >= 100 ? 'pg-badge-review' : ($utilization >= 70 ? 'pg-badge-progress' : 'pg-badge-completed') }}">{{ $utilization >= 100 ? 'Overloaded' : ($utilization >= 70 ? 'Busy' : 'Available') }}</span></td>
                    </tr>
                @empty<tr><td colspan="8" class="py-10 text-center text-slate-500">No active users.</td></tr>@endforelse
                </tbody>
            </table>
        </div></div>
    </div>
</x-app-layout>
