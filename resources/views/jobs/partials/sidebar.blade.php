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
            <div class="font-semibold">{{ $job->client?->clientServiceNames() ?? '-' }}</div>
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
