<x-app-layout>
    <x-slot name="header">Archive</x-slot>
    <div class="space-y-6">
        <div class="flex justify-between items-center"><div><h2 class="pg-title">Archive</h2><p class="pg-subtitle mt-1">Archived jobs and retained production files.</p></div><a href="{{ route('archive.assets') }}" class="pg-btn-secondary">Archived Assets</a></div>
        @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>@endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Archived Jobs</div><div class="pg-stat-value">{{ $counts['jobs'] }}</div></div></div><div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Archived Assets</div><div class="pg-stat-value">{{ $counts['assets'] }}</div></div></div></div>
        <div class="pg-card"><div class="pg-card-body overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Job</th><th>Client</th><th>Project</th><th>Stage</th><th>Archived At</th><th></th></tr></thead>
            <tbody>@forelse($jobs as $job)<tr class="border-b last:border-b-0"><td class="py-4"><a class="font-semibold" href="{{ route('jobs.show', $job) }}">{{ $job->job_number }} — {{ $job->title }}</a></td><td>{{ $job->client?->name ?? '-' }}</td><td>{{ $job->project?->name ?? '-' }}</td><td>{{ $job->currentWorkflowStage?->name ?? '-' }}</td><td>{{ $job->archived_at?->format('Y-m-d H:i') ?? '-' }}</td><td><form method="POST" action="{{ route('archive.restore', $job) }}">@csrf @method('DELETE')<button class="pg-btn-secondary">Restore</button></form></td></tr>@empty<tr><td colspan="6" class="py-10 text-center text-slate-500">No archived jobs.</td></tr>@endforelse</tbody>
        </table><div class="mt-6">{{ $jobs->links() }}</div></div></div>
    </div>
</x-app-layout>
