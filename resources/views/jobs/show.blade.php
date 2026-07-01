<x-app-layout>
    <x-slot name="header">
        Job Details
    </x-slot>

    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $job->job_number }}</h2>
                <p class="pg-subtitle">{{ $job->title }}</p>
            </div>

            <a href="{{ route('jobs.index') }}" class="pg-btn-secondary">
                Back to Jobs
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="xl:col-span-2 pg-card">
                <div class="pg-card-body">
                    <h3 class="text-lg font-bold mb-4">Brief</h3>
                    <p class="text-slate-700 whitespace-pre-line">
                        {{ $job->brief ?? 'No brief provided.' }}
                    </p>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body space-y-4">
                    <div>
                        <div class="pg-stat-label">Client</div>
                        <div class="font-semibold">{{ $job->client?->name }}</div>
                    </div>

                    <div>
                        <div class="pg-stat-label">Project</div>
                        <div class="font-semibold">{{ $job->project?->name ?? '-' }}</div>
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
                </div>
            </div>

        </div>

    </div>
</x-app-layout>