<x-app-layout>
    <x-slot name="header">
        Jobs Management
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Jobs Management</h2>
                <p class="pg-subtitle">All creative jobs in the studio workflow.</p>
            </div>

            <a href="{{ route('jobs.create') }}" class="pg-btn-primary">
                + New Job
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Total Jobs</div>
                    <div class="pg-stat-value">{{ $jobs->count() }}</div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">In Progress</div>
                    <div class="pg-stat-value">
                        {{ $jobs->where('currentWorkflowStage.code', 'DESIGN')->count() }}
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">QA Review</div>
                    <div class="pg-stat-value">
                        {{ $jobs->where('currentWorkflowStage.code', 'QA')->count() }}
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Urgent</div>
                    <div class="pg-stat-value">
                        {{ $jobs->whereIn('priority', ['URGENT', 'CRITICAL'])->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-slate-500">
                                <th class="py-3">Job No.</th>
                                <th>Title</th>
                                <th>Client</th>
                                <th>Project</th>
                                <th>Stage</th>
                                <th>Priority</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($jobs as $job)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-4 font-semibold">
                                        {{ $job->job_number }}
                                    </td>

                                    <td>
                                        {{ $job->title }}
                                    </td>

                                    <td>
                                        {{ $job->client?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $job->project?->name ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="pg-badge pg-badge-new">
                                            {{ $job->currentWorkflowStage?->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $job->priority }}
                                    </td>

                                    <td>
                                        {{ $job->created_at?->format('Y-m-d') }}
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="pg-btn-secondary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-500">
                                        No jobs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>