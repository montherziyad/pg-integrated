<x-app-layout>
    <x-slot name="header">Traffic Board</x-slot>
    <div class="space-y-6">
        <div><h2 class="pg-title">Traffic Board</h2><p class="pg-subtitle mt-1">Live workflow view for all active jobs.</p></div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 items-start">
            @forelse($stages as $stage)
                <div class="pg-card">
                    <div class="pg-card-body">
                        <div class="flex justify-between items-center mb-5"><h3 class="font-bold">{{ $stage->name }}</h3><span class="pg-badge pg-badge-new">{{ $stage->jobs->count() }}</span></div>
                        <div class="space-y-3">
                            @forelse($stage->jobs as $job)
                                <a href="{{ route('jobs.show', $job) }}" class="block rounded-xl border border-slate-200 p-4 hover:bg-slate-50">
                                    <div class="flex justify-between gap-2"><span class="font-semibold">{{ $job->job_number }}</span><span class="text-xs text-slate-500">{{ $job->priority }}</span></div>
                                    <div class="mt-2 text-sm">{{ $job->title }}</div>
                                    <div class="mt-2 text-xs text-slate-500">{{ $job->client?->name ?? '-' }}</div>
                                    <div class="mt-3 flex justify-between text-xs"><span>{{ $job->assignments->pluck('assignee.name')->filter()->join(', ') ?: 'Unassigned' }}</span><span>{{ $job->final_due_at?->format('Y-m-d') ?? '-' }}</span></div>
                                </a>
                            @empty
                                <div class="py-8 text-center text-sm text-slate-500">No jobs</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="pg-card"><div class="pg-card-body text-center text-slate-500">No active workflow stages.</div></div>
            @endforelse
        </div>
    </div>
</x-app-layout>
