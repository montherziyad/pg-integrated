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
        $currentStep = 'Client Service Review';
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
        [
            'label' => 'Brief',
            'owner' => 'Traffic / Client Service',
            'meaning' => 'The request, email, or brief has been received and needs to be understood before work starts.',
            'action' => 'Check the brief, attachments, client, project, category, and due dates.',
        ],
        [
            'label' => 'Traffic Review',
            'owner' => 'Traffic',
            'meaning' => 'Traffic verifies that the job is valid and ready to be assigned.',
            'action' => 'Confirm priority, scope, required team, and missing information before assignment.',
        ],
        [
            'label' => 'Assigned',
            'owner' => 'Traffic',
            'meaning' => 'The job has designers/team members and leads attached to it.',
            'action' => 'Make sure the assigned team and lead are correct.',
        ],
        [
            'label' => 'Team Due',
            'owner' => 'Designer / Lead',
            'meaning' => 'The assigned team must confirm when they can hand over files to Traffic.',
            'action' => 'Designer or lead confirms the expected delivery date and time.',
        ],
        [
            'label' => 'Production',
            'owner' => 'Designer / Creative Team',
            'meaning' => 'The team is working on the creative output.',
            'action' => 'Prepare the requested files according to the brief and delivery date.',
        ],
        [
            'label' => 'Handover',
            'owner' => 'Traffic',
            'meaning' => 'The designer has submitted files or a link to Traffic.',
            'action' => 'Traffic reviews the files. If correct, open Delivery / Handover and mark it Checked. If not, request revision.',
        ],
        [
            'label' => 'Traffic Checked',
            'owner' => 'Traffic',
            'meaning' => 'Traffic has checked the output and it is ready for Client Service review.',
            'action' => 'Completed by Traffic. The checked delivery is now waiting for Client Service.',
        ],
        [
            'label' => 'Client Service',
            'owner' => 'Client Service',
            'meaning' => 'Client Service reviews the final output before publishing it to the client portal.',
            'action' => 'Approve, add delivery note/link, and publish to client portal when ready.',
        ],
        [
            'label' => 'Published',
            'owner' => 'Client / Archive',
            'meaning' => 'The final delivery link is visible to the client.',
            'action' => 'Confirm visibility and archive after completion.',
        ],
    ];

    $activeIndex = match (true) {
        $job->delivery_review_status === 'published' => 8,
        $job->delivery_review_status === 'checked' => 7,
        $job->employee_handover_status === 'submitted_to_traffic' => 5,
        (bool) $job->production_due_at => 4,
        $designers->isNotEmpty() || $leads->isNotEmpty() => 2,
        default => 1,
    };
@endphp

<div class="pg-card {{ $statusTone }} border-l-4" x-data="{ selectedStep: {{ $activeIndex }} }">
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
                <button
                    type="button"
                    @click="selectedStep = {{ $index }}"
                    class="rounded-2xl border p-3 text-center text-xs font-bold transition hover:border-slate-950 hover:text-slate-950 {{ $current ? 'border-slate-950 bg-slate-950 text-white' : ($done ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-white text-slate-400') }}"
                >
                    {{ $step['label'] }}
                </button>
            @endforeach
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-5">
            @foreach($steps as $index => $step)
                <div x-show="selectedStep === {{ $index }}" x-cloak>
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">Step guide</div>
                            <h4 class="mt-2 text-xl font-black">{{ $step['label'] }}</h4>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $step['meaning'] }}</p>
                        </div>

                        <div class="rounded-2xl border {{ $index === $activeIndex ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50' }} p-4 lg:min-w-[360px]">
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">
                                {{ $index === $activeIndex ? 'Required now' : 'For reference only' }}
                            </div>
                            <div class="mt-2 font-bold text-slate-950">{{ $step['action'] }}</div>
                            <div class="mt-2 text-sm text-slate-500">Owner: {{ $step['owner'] }}</div>

                            @if($index === $activeIndex && $step['label'] === 'Handover')
                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    @if(Auth::user()?->canAccessScreen('deliveries'))
                                        <a href="{{ route('deliveries.edit', $job) }}" class="inline-flex rounded-xl bg-slate-950 px-4 py-2 text-sm font-bold text-white">
                                            Open Delivery / Handover
                                        </a>
                                    @endif

                                    @if($job->employee_handover_link)
                                        <a href="{{ $job->employee_handover_link }}" target="_blank" rel="noopener" class="inline-flex rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:border-slate-950">
                                            View submitted link
                                        </a>
                                    @else
                                        <a href="#employee-handover" class="inline-flex rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:border-slate-950">
                                            View attachments / link
                                        </a>
                                    @endif

                                    @if(Auth::user()?->canAccessScreen('traffic_board') || Auth::user()?->canAccessScreen('deliveries') || Auth::user()?->canAccessScreen('team_workload'))
                                        <form method="POST" action="{{ route('jobs.traffic-approve-handover', $job) }}" onsubmit="return confirm('Approve this handover and send it to Client Service?');">
                                            @csrf
                                            <button type="submit" class="inline-flex rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                                                Approve & send to Client Service
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            @if($index === $activeIndex && $step['label'] === 'Client Service')
                                <div class="mt-4 space-y-4">
                                    @if($job->final_delivery_path || $job->employee_handover_link)
                                        <a href="{{ $job->final_delivery_path ?: $job->employee_handover_link }}" target="_blank" rel="noopener" class="inline-flex rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:border-slate-950">
                                            View final link before approval
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('jobs.client-service-publish', $job) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4" onsubmit="return confirm('Approve and publish this delivery to the client portal?');">
                                        @csrf
                                        <label class="block text-sm font-bold text-emerald-950">Client delivery note</label>
                                        <textarea name="client_notes" rows="3" class="mt-2 w-full rounded-xl border-emerald-200 bg-white text-sm" placeholder="Optional note visible internally and useful for the client delivery record.">{{ old('client_notes', $job->client_notes) }}</textarea>
                                        <button type="submit" class="mt-3 inline-flex rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                                            Approve & send to Client
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('jobs.client-service-revision', $job) }}" enctype="multipart/form-data" class="rounded-2xl border border-red-200 bg-red-50 p-4" onsubmit="return confirm('Send this job back for revision?');">
                                        @csrf
                                        <label class="block text-sm font-bold text-red-950">Revision notes</label>
                                        <textarea name="revision_notes" rows="3" required minlength="5" class="mt-2 w-full rounded-xl border-red-200 bg-white text-sm" placeholder="Explain exactly what needs to be changed before publishing to the client.">{{ old('revision_notes') }}</textarea>

                                        <label class="mt-3 block text-sm font-bold text-red-950">Revision attachments</label>
                                        <input type="file" name="revision_files[]" multiple class="mt-2 block w-full rounded-xl border border-red-200 bg-white p-3 text-sm">
                                        <p class="mt-1 text-xs text-red-700">Optional: upload screenshots, marked PDFs, or reference files for Traffic and the production team.</p>

                                        <button type="submit" class="mt-3 inline-flex rounded-xl bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700">
                                            Request revision
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
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
