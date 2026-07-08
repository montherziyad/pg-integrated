@php
    $canConfirmDue = $job->isAssignedTo(Auth::user())
        || Auth::user()?->canAccessScreen('traffic_board')
        || Auth::user()?->canAccessScreen('team_workload');
@endphp

<div class="pg-card border-l-4 border-l-blue-500">
    <div class="pg-card-body space-y-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
            <div>
                <h3 class="text-lg font-bold">Production delivery commitment</h3>
                <p class="mt-1 text-sm text-slate-500">The assigned designer or lead confirms when this job can be handed over to Traffic.</p>
            </div>

            @if($job->production_due_at)
                <span class="pg-badge pg-badge-review">Due confirmed</span>
            @else
                <span class="pg-badge pg-badge-progress">Waiting for team response</span>
            @endif
        </div>

        @if($job->production_due_at)
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-950">
                <div class="font-bold">Expected handover to Traffic: {{ $job->production_due_at?->format('Y-m-d H:i') }}</div>
                <div class="mt-1">
                    Confirmed by {{ $job->productionDueConfirmer?->name ?? 'PG employee' }}
                    at {{ $job->production_due_confirmed_at?->format('Y-m-d H:i') ?? '-' }}.
                </div>
                @if($job->production_due_notes)
                    <div class="mt-3 text-blue-900">{{ $job->production_due_notes }}</div>
                @endif
            </div>
        @endif

        @if($canConfirmDue)
            <form method="POST" action="{{ route('jobs.production-due', $job) }}" class="grid gap-4 md:grid-cols-[1fr_1.5fr_auto] md:items-end">
                @csrf

                <div>
                    <label class="block mb-2 font-semibold">Expected delivery to Traffic</label>
                    <input
                        name="production_due_at"
                        type="datetime-local"
                        value="{{ old('production_due_at', $job->production_due_at?->format('Y-m-d\\TH:i')) }}"
                        class="w-full rounded-xl border-slate-300"
                        required
                    >
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Notes</label>
                    <input
                        name="production_due_notes"
                        value="{{ old('production_due_notes', $job->production_due_notes) }}"
                        placeholder="Example: First route ready by noon, source files by end of day."
                        class="w-full rounded-xl border-slate-300"
                    >
                </div>

                <button class="pg-btn-primary">Confirm date</button>
            </form>
        @endif
    </div>
</div>
