<x-app-layout>

    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">

        <!-- Welcome -->
        <div>
            <h2 class="pg-title">
                Welcome back, {{ Auth::user()->name }}
            </h2>

            <p class="pg-subtitle mt-1">
                PG Integrated Creative Operations Dashboard
            </p>
        </div>

       <!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Total Jobs</div>
            <div class="pg-stat-value">
                {{ $totalJobs }}
            </div>
        </div>
    </div>

    <a href="{{ route('email-intakes.index') }}" class="pg-card hover:border-slate-400">
        <div class="pg-card-body">
            <div class="pg-stat-label">Pending Email Intake</div>
            <div class="pg-stat-value">{{ $pendingEmailIntakes }}</div>
        </div>
    </a>

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Urgent Jobs</div>
            <div class="pg-stat-value text-red-600">
                {{ $urgentJobs }}
            </div>
        </div>
    </div>

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Clients</div>
            <div class="pg-stat-value">
                {{ $totalClients }}
            </div>
        </div>
    </div>

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Projects</div>
            <div class="pg-stat-value">
                {{ $totalProjects }}
            </div>
        </div>
    </div>

</div>
        <!-- Two Columns -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- Traffic Board -->
            <div class="pg-card">
                <div class="pg-card-body">

                    <div class="flex justify-between items-center mb-6"><h3 class="text-lg font-bold">Traffic Board</h3><a href="{{ route('traffic.index') }}" class="text-sm font-semibold text-slate-600">View board →</a></div>

                    <div class="space-y-4">

                        @forelse($workflowStages as $stage)
                            <div class="flex justify-between"><span>{{ $stage->name }}</span><span class="pg-badge pg-badge-new">{{ $stage->jobs_count }}</span></div>
                        @empty
                            <div class="text-sm text-slate-500">No workflow stages.</div>
                        @endforelse

                    </div>

                </div>
            </div>

            <!-- Team Workload -->
            <div class="pg-card">
                <div class="pg-card-body">

                    <div class="flex justify-between items-center mb-6"><h3 class="text-lg font-bold">Team Workload</h3><a href="{{ route('workload.index') }}" class="text-sm font-semibold text-slate-600">View workload →</a></div>

                    <table class="w-full">

                        <thead>
                            <tr class="text-left border-b">
                                <th class="pb-3">Employee</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Jobs</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($workloadUsers as $user)
                                @php($busy = (float) $user->assigned_hours >= $user->capacity_hours)
                                <tr class="border-b last:border-b-0"><td class="py-3">{{ $user->name }}</td><td><span class="pg-badge {{ $busy ? 'pg-badge-progress' : 'pg-badge-completed' }}">{{ $busy ? 'Busy' : 'Available' }}</span></td><td>{{ $user->active_jobs_count }}</td></tr>
                            @empty<tr><td colspan="3" class="py-6 text-center text-slate-500">No team data.</td></tr>@endforelse
                        </tbody>

                    </table>

                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="pg-card"><div class="pg-card-body"><div class="flex justify-between mb-5"><h3 class="text-lg font-bold">Latest Jobs</h3><a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-slate-600">All jobs →</a></div><div class="space-y-3">@forelse($latestJobs as $job)<a href="{{ route('jobs.show', $job) }}" class="flex justify-between gap-4 border-b pb-3 last:border-b-0"><span><strong>{{ $job->job_number }}</strong><span class="block text-sm text-slate-500">{{ $job->title }}</span></span><span class="text-sm">{{ $job->currentWorkflowStage?->name ?? '-' }}</span></a>@empty<div class="text-slate-500">No jobs.</div>@endforelse</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-5">Latest Activities</h3><div class="space-y-3">@forelse($latestActivities as $activity)<div class="border-b pb-3 last:border-b-0"><div class="font-semibold">{{ $activity->description ?? $activity->activity_type }}</div><div class="text-sm text-slate-500">{{ $activity->user?->name ?? 'System' }} · {{ $activity->activity_at?->diffForHumans() }}</div></div>@empty<div class="text-slate-500">No activities.</div>@endforelse</div></div></div>
        </div>

    </div>

</x-app-layout>
