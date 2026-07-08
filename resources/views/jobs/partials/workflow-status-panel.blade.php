@php
    $designers = $job->assignedDesigners();
    $leads = $job->assignmentLeads();
    $clientService = $job->client?->clientServiceUsers ?? collect();

    $currentStep = 'Traffic review';
    $nextAction = 'Review the brief and assign the right team.';
    $nextOwner = 'Traffic';
    $statusTone = 'border-slate-200 bg-white';

    if ($job->employee_handover_status === 'submitted_to_traffic') {
        $currentStep = 'Submitted to Traffic';
        $nextAction = 'Traffic should review the handover files, then send to Client Service.';
        $nextOwner = 'Traffic';
        $statusTone = 'border-blue-200 bg-blue-50';
    } elseif ($job->production_due_at) {
        $currentStep = 'In production';
        $nextAction = 'Team should prepare files and submit the handover to Traffic.';
        $nextOwner = $designers->pluck('name')->implode(', ') ?: 'Assigned team';
        $statusTone = 'border-emerald-200 bg-emerald-50';
    } elseif ($designers->isNotEmpty() || $leads->isNotEmpty()) {
        $currentStep = 'Waiting for team delivery date';
        $nextAction = 'Designer or lead must confirm the expected delivery date.';
        $nextOwner = $leads->pluck('name')->implode(', ') ?: ($designers->pluck('name')->implode(', ') ?: 'Assigned team');
        $statusTone = 'border-amber-200 bg-amber-50';
    }

    if ($job->delivery_review_status === 'checked') {
        $currentStep = 'Checked by Traffic';
        $nextAction = 'Client Service should approve and publish the final link to the client portal.';
        $nextOwner = $clientService->pluck('name')->implode(', ') ?: ($job->responsibleUser?->name ?? 'Client Service');
        $statusTone = 'border-purple-200 bg-purple-50';
    }

    if ($job->delivery_review_status === 'published') {
        $currentStep = 'Published to Client';
        $nextAction = 'Confirm client visibility and move to archive when completed.';
        $nextOwner = 'Client Service / Archive';
        $statusTone = 'border-green-200 bg-green-50';
    }

    $steps = [
        'Brief',
        'Traffic Review',
        'Assigned',
        'Team Due',
        'Production',
        'Handover',
        'Traffic Checked',
        'Client Service',
        'Published',
    ];

    $activeIndex = match (true) {
        $job->delivery_review_status === 'published' => 8,
        $job->delivery_review_status === 'checked' => 6,
        $job->employee_handover_status === 'submitted_to_traffic' => 5,
        (bool) $job->production_due_at => 4,
        $designers->isNotEmpty() || $leads->isNotEmpty() => 2,
        default => 1,
    };
@endphp

<div class="pg-card {{ $statusTone }} border-l-4">
    <div class="pg-card-body space-y-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
            <div>
                <div class="text-xs font-black uppercase tracking-[.22em] text-slate-400">Workflow status</div>
                <h3 class="mt-2 text-2xl font-black">{{ $currentStep }}</h3>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">{{ $nextAction }}</p>
            </div>

            <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm xl:min-w-[280px]">
                <div class="text-xs font-black uppercase tracking-wide text-slate-400">Next owner</div>
                <div class="mt-2 text-lg font-bold text-slate-950">{{ $nextOwner ?: '-' }}</div>
                <div class="mt-3 text-xs text-slate-500">
                    Team due:
                    <span class="font-bold text-slate-700">{{ $job->production_due_at?->format('d M Y, h:i A') ?? 'Not confirmed' }}</span>
                </div>
            </div>
        </div>

        <div class="grid gap-2 md:grid-cols-3 xl:grid-cols-9">
            @foreach($steps as $index => $step)
                @php
                    $done = $index < $activeIndex;
                    $current = $index === $activeIndex;
                @endphp
                <div class="rounded-2xl border p-3 text-center text-xs font-bold {{ $current ? 'border-slate-950 bg-slate-950 text-white' : ($done ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-white text-slate-400') }}">
                    {{ $step }}
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="text-xs font-black uppercase tracking-wide text-slate-400">Designers / Team</div>
                <div class="mt-2 space-y-1 font-semibold">
                    @forelse($designers as $designer)
                        <div>{{ $designer->name }}</div>
                    @empty
                        <div class="text-slate-400">Not assigned</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="text-xs font-black uppercase tracking-wide text-slate-400">Leads / Supervisors</div>
                <div class="mt-2 space-y-1 font-semibold">
                    @forelse($leads as $lead)
                        <div>{{ $lead->name }}</div>
                    @empty
                        <div class="text-slate-400">Not assigned</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="text-xs font-black uppercase tracking-wide text-slate-400">Client Service</div>
                <div class="mt-2 space-y-1 font-semibold">
                    @forelse($clientService as $serviceUser)
                        <div>{{ $serviceUser->name }}</div>
                    @empty
                        <div>{{ $job->responsibleUser?->name ?? 'Not assigned' }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
