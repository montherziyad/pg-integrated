<x-app-layout>
    <x-slot name="header">Reports</x-slot>
    <div class="space-y-6">
        <div><h2 class="pg-title">Operations Reports</h2><p class="pg-subtitle mt-1">Live delivery, utilization and client summaries.</p></div>
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach([['Total Jobs',$totalJobs],['Active Jobs',$activeJobs],['Overdue Jobs',$overdueJobs],['Archived Jobs',$archivedJobs]] as [$label,$value])<div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">{{ $label }}</div><div class="pg-stat-value">{{ $value }}</div></div></div>@endforeach
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Estimated Hours</div><div class="pg-stat-value">{{ number_format($estimatedHours, 1) }}</div></div></div><div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Actual Hours</div><div class="pg-stat-value">{{ number_format($actualHours, 1) }}</div></div></div></div>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Top Clients</h3><table class="w-full text-sm"><thead><tr class="border-b text-left text-slate-500"><th class="py-3">Client</th><th>Projects</th></tr></thead><tbody>@forelse($clients as $client)<tr class="border-b last:border-b-0"><td class="py-3">{{ $client->name }}</td><td>{{ $client->projects_count }}</td></tr>@empty<tr><td colspan="2" class="py-6 text-center">No data</td></tr>@endforelse</tbody></table></div></div>
            <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Team Delivery</h3><table class="w-full text-sm"><thead><tr class="border-b text-left text-slate-500"><th class="py-3">Employee</th><th>Completed</th><th>Actual Hours</th></tr></thead><tbody>@forelse($team as $user)<tr class="border-b last:border-b-0"><td class="py-3">{{ $user->name }}</td><td>{{ $user->completed_jobs_count }}</td><td>{{ number_format((float) $user->actual_hours, 1) }}</td></tr>@empty<tr><td colspan="3" class="py-6 text-center">No data</td></tr>@endforelse</tbody></table></div></div>
        </div>
        <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Assignment Status</h3><div class="grid grid-cols-2 md:grid-cols-5 gap-4">@forelse($assignmentStatus as $status)<div class="rounded-xl border border-slate-200 p-4"><div class="text-sm text-slate-500">{{ str($status->status)->replace('_', ' ')->title() }}</div><div class="text-2xl font-bold mt-2">{{ $status->total }}</div></div>@empty<div class="text-slate-500">No assignment data.</div>@endforelse</div></div></div>
    </div>
</x-app-layout>
