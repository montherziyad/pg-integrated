<x-app-layout>
    <x-slot name="header">Traffic Board</x-slot>
    <div class="space-y-6">
        <div>
            <h2 class="pg-title">Traffic Board</h2>
            <p class="pg-subtitle mt-1">A simplified operations board showing exactly where each active job is stopped and what action is required next.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 items-start xl:grid-cols-5">
            @forelse($stages as $stage)
                <div class="pg-card">
                    <div class="pg-card-body">
                        <div class="mb-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold">{{ $stage->name }}</h3>
                                    @if(! empty($stage->description))
                                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ $stage->description }}</p>
                                    @endif
                                </div>
                                <span class="pg-badge pg-badge-new">{{ $stage->jobs->count() }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @forelse($stage->jobs as $job)
                                @php
                                    $designers = $job->assignedDesignerNames() ?: 'Unassigned';
                                    $leads = $job->assignmentLeadNames();
                                    $statusLabel = match (true) {
                                        $job->delivery_review_status === 'checked' => 'Traffic Checked',
                                        $job->employee_handover_status === 'submitted_to_traffic' => 'Needs Traffic Review',
                                        (bool) $job->production_due_at => 'In Production',
                                        $job->assignments->isNotEmpty() => 'Waiting Team Date',
                                        default => 'Needs Review',
                                    };

                                    $actionRoute = match ($stage->code) {
                                        'handover_review', 'client_service_review' => route('deliveries.edit', $job),
                                        default => route('jobs.show', $job),
                                    };

                                    $actionLabel = match ($stage->code) {
                                        'traffic_review' => 'Review & assign',
                                        'waiting_team_date' => 'Check team date',
                                        'production' => 'Monitor job',
                                        'handover_review' => 'Review handover',
                                        'client_service_review' => 'Client approval',
                                        default => 'Open job',
                                    };
                                @endphp

                                <div class="rounded-xl border border-slate-200 bg-white p-4">
                                    <div class="flex justify-between gap-2">
                                        <span class="font-semibold">{{ $job->job_number }}</span>
                                        <span class="text-xs font-bold text-slate-500">{{ $job->priority }}</span>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $job->title }}</div>
                                    <div class="mt-2 text-xs text-slate-500">{{ $job->client?->name ?? '-' }}</div>
                                    <div class="mt-3 rounded-xl bg-slate-50 p-3 text-xs">
                                        <div class="font-bold text-slate-700">{{ $statusLabel }}</div>
                                        <div class="mt-2 text-slate-500">Designers: <span class="font-semibold text-slate-700">{{ $designers }}</span></div>
                                        @if($leads)
                                            <div class="mt-1 text-slate-500">Leads: <span class="font-semibold text-slate-700">{{ $leads }}</span></div>
                                        @endif
                                        <div class="mt-1 text-slate-500">Team due: <span class="font-semibold text-slate-700">{{ $job->production_due_at?->format('d M, h:i A') ?? 'Not confirmed' }}</span></div>
                                        <div class="mt-1 text-slate-500">Final due: <span class="font-semibold text-slate-700">{{ $job->final_due_at?->format('d M, h:i A') ?? '-' }}</span></div>
                                    </div>

                                    <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                                        <div class="font-black uppercase tracking-wide">Required now</div>
                                        <div class="mt-1 leading-5">{{ $stage->action }}</div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <a href="{{ $actionRoute }}" class="inline-flex rounded-xl bg-slate-950 px-3 py-2 text-xs font-bold text-white hover:bg-slate-800">
                                            {{ $actionLabel }}
                                        </a>

                                        @if($job->employee_handover_link && in_array($stage->code, ['handover_review', 'client_service_review'], true))
                                            <a href="{{ $job->employee_handover_link }}" target="_blank" rel="noopener" class="inline-flex rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:border-slate-950">
                                                View link
                                            </a>
                                        @endif
                                    </div>
                                </div>
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
