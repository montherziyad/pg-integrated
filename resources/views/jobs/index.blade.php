<x-app-layout>
    <x-slot name="header">
        {{ $isHandoverView ?? false ? 'Employee Handover' : 'Jobs Management' }}
    </x-slot>

    @php
        $isHandoverView = $isHandoverView ?? false;
        $canCreateJobs = Auth::user()?->canAccessScreen('traffic_board') || Auth::user()?->canAccessScreen('email_intake') || Auth::user()?->canAccessScreen('team_workload');
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $isHandoverView ? 'My Handover Tasks' : 'Jobs Management' }}</h2>
                <p class="pg-subtitle">{{ $isHandoverView ? 'Submit your finished files or links to Traffic for review.' : 'Jobs visible according to your role permissions.' }}</p>
            </div>

            @if($canCreateJobs)
                <a href="{{ route('jobs.create') }}" class="pg-btn-primary">
                    + New Job
                </a>
            @endif
        </div>

        @include('admin.partials.search', [
            'action' => $isHandoverView ? route('handovers.index') : route('jobs.index'),
            'placeholder' => $isHandoverView
                ? 'Search my handover tasks by job number, title, client, project, status...'
                : 'Search jobs by job number, title, client, project, responsible, priority...',
        ])

        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Visible Jobs</div><div class="pg-stat-value">{{ $jobs->count() }}</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Pending Handover</div><div class="pg-stat-value">{{ $jobs->where('employee_handover_status', 'not_submitted')->count() }}</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Submitted</div><div class="pg-stat-value">{{ $jobs->where('employee_handover_status', 'submitted_to_traffic')->count() }}</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Urgent</div><div class="pg-stat-value">{{ $jobs->whereIn('priority', ['URGENT', 'CRITICAL'])->count() }}</div></div></div>
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
                                <th>Assigned / Responsible</th>
                                <th>Stage</th>
                                <th>Handover</th>
                                <th>Due</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($jobs as $job)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-4 font-semibold">{{ $job->job_number }}</td>
                                    <td><div class="font-semibold">{{ $job->title }}</div><div class="text-xs text-slate-500">{{ $job->priority }}</div></td>
                                    <td>{{ $job->client?->name ?? '-' }}</td>
                                    <td>{{ $job->project?->name ?? '-' }}</td>
                                    <td>
                                        <div class="font-semibold">{{ $job->responsibleUser?->name ?? $job->assignments->pluck('assignee.name')->filter()->unique()->implode(', ') ?: '-' }}</div>
                                        <div class="text-xs text-slate-500">PG Employee</div>
                                    </td>
                                    <td><span class="pg-badge pg-badge-new">{{ $job->currentWorkflowStage?->name ?? '-' }}</span></td>
                                    <td>
                                        @if($job->employee_handover_status === 'submitted_to_traffic')
                                            <span class="pg-badge pg-badge-review">Submitted to Traffic</span>
                                            <div class="mt-1 text-xs text-slate-500">{{ $job->employee_handover_submitted_at?->format('Y-m-d H:i') }}</div>
                                        @else
                                            <span class="pg-badge pg-badge-progress">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $job->final_due_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="text-right"><a href="{{ route('jobs.show', $job->id) }}" class="pg-btn-secondary">Open</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="py-12 text-center text-slate-500">No jobs found for your role.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
