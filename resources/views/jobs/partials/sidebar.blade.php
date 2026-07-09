@php
    $clientServiceNames = $job->client?->clientServiceNames();
    $clientServiceNames = $clientServiceNames && $clientServiceNames !== '-'
        ? $clientServiceNames
        : ($job->responsibleUser?->name ?? $job->client?->accountManager?->name ?? '-');

    $handoverStatus = $job->employee_handover_status === 'submitted_to_traffic'
        ? 'Submitted to Traffic'
        : 'Pending Handover';

    $deliveryStatus = str($job->delivery_review_status ?? 'draft')->replace('_', ' ')->title();
@endphp

<div class="pg-card">
    <div class="pg-card-body space-y-4">
        <h3 class="text-lg font-bold">Job summary</h3>

        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                <div class="pg-stat-label">Priority</div>
                <div class="font-bold">{{ $job->priority }}</div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                <div class="pg-stat-label">Stage</div>
                <div class="font-bold">{{ $job->currentWorkflowStage?->name ?? '-' }}</div>
            </div>

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-3">
                <div class="pg-stat-label">Handover</div>
                <div class="font-bold text-blue-950">{{ $handoverStatus }}</div>
            </div>

            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-3">
                <div class="pg-stat-label">Delivery Review</div>
                <div class="font-bold text-purple-950">{{ $deliveryStatus }}</div>
            </div>
        </div>

        <div>
            <div class="pg-stat-label">Client</div>
            <div class="font-semibold">{{ $job->client?->name ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Project</div>
            <div class="font-semibold">{{ $job->project?->name ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Client Service</div>
            <div class="font-semibold">{{ $clientServiceNames }}</div>
            @if($job->responsibleUser && $clientServiceNames === $job->responsibleUser->name)
                <div class="mt-1 text-xs text-slate-500">Job responsible / fallback owner</div>
            @endif
        </div>

        <div>
            <div class="pg-stat-label">Category</div>
            <div class="font-semibold">{{ $job->category?->name ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Team Delivery</div>
            <div class="font-semibold">{{ $job->production_due_at?->format('d M Y, h:i A') ?? 'Not confirmed' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Final Due</div>
            <div class="font-semibold">{{ $job->final_due_at?->format('d M Y, h:i A') ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Estimated Hours</div>
            <div class="font-semibold">{{ $job->estimated_hours }}</div>
        </div>
    </div>
</div>
