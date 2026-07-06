<div class="pg-card">
    <div class="pg-card-body space-y-4">
        <div>
            <div class="pg-stat-label">Client</div>
            <div class="font-semibold">{{ $job->client?->name ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Project</div>
            <div class="font-semibold">{{ $job->project?->name ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Job Responsible</div>
            <div class="font-semibold">{{ $job->client?->clientServiceNames() ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Category</div>
            <div class="font-semibold">{{ $job->category?->name ?? '-' }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Priority</div>
            <div class="font-semibold">{{ $job->priority }}</div>
        </div>

        <div>
            <div class="pg-stat-label">Workflow Stage</div>
            <div class="font-semibold">
                {{ $job->currentWorkflowStage?->name ?? '-' }}
            </div>
        </div>

        <div>
            <div class="pg-stat-label">Estimated Hours</div>
            <div class="font-semibold">
                {{ $job->estimated_hours }}
            </div>
        </div>
    </div>
</div>