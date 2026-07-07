<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $user = Auth::user();
        $can = fn (string $screen): bool => $user?->canAccessScreen($screen) ?? false;
        $canAny = fn (array $screens): bool => collect($screens)->contains(fn ($screen) => $can($screen));
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Welcome back, {{ $user->name }}</h2>
                <p class="pg-subtitle mt-1">Your dashboard is filtered by your role permissions.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($can('email_intake'))<a href="{{ route('email-intakes.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Review emails</a>@endif
                @if($can('traffic_board'))<a href="{{ route('traffic.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold">Traffic board</a>@endif
                @if($can('employee_handover'))<a href="{{ route('handovers.index') }}" class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-900">My handovers</a>@endif
                @if($can('client_requests'))<a href="{{ route('admin.client-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold">Client requests</a>@endif
            </div>
        </div>

        @if($employeeNotifications->isNotEmpty())
            <section class="pg-card border-l-4 border-l-blue-500">
                <div class="pg-card-body">
                    <div class="mb-4 text-xs font-black uppercase tracking-[.2em] text-blue-500">Notifications</div>
                    <div class="space-y-3">
                        @foreach($employeeNotifications as $notification)
                            <a href="{{ $notification->job ? route('jobs.show', $notification->job) : route('dashboard') }}" class="block rounded-2xl border border-blue-100 bg-blue-50 p-4 hover:border-blue-300">
                                <div class="font-bold text-blue-950">{{ $notification->title }}</div>
                                <div class="mt-1 text-sm text-blue-800">{{ $notification->body }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="pg-card border-l-4 border-l-amber-400">
            <div class="pg-card-body">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">My work</div>
                        <h3 class="mt-2 text-2xl font-black">Assigned jobs & handover</h3>
                        <p class="mt-1 text-sm text-slate-500">These are the jobs assigned to you or where you are the responsible employee.</p>
                    </div>
                    @if($can('employee_handover'))<a href="{{ route('handovers.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Open Handover →</a>@endif
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-3xl bg-slate-950 p-5 text-white"><div class="text-sm font-bold text-slate-300">My assigned jobs</div><div class="mt-3 text-5xl font-extrabold">{{ $myAssignedJobs->count() }}</div></div>
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5"><div class="text-sm font-bold text-amber-700">Pending handover</div><div class="mt-3 text-5xl font-extrabold text-amber-950">{{ $myAssignedJobs->where('employee_handover_status', 'not_submitted')->count() }}</div></div>
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5"><div class="text-sm font-bold text-emerald-700">Submitted to traffic</div><div class="mt-3 text-5xl font-extrabold text-emerald-950">{{ $myAssignedJobs->where('employee_handover_status', 'submitted_to_traffic')->count() }}</div></div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Job</th><th>Client</th><th>Stage</th><th>Due</th><th>Handover</th><th></th></tr></thead>
                        <tbody>
                            @forelse($myAssignedJobs as $job)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-4"><div class="font-bold">{{ $job->title }}</div><div class="text-xs text-slate-500">{{ $job->job_number }}</div></td>
                                    <td>{{ $job->client?->name ?? '-' }}</td>
                                    <td>{{ $job->currentWorkflowStage?->name ?? '-' }}</td>
                                    <td>{{ $job->final_due_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td>@if($job->employee_handover_status === 'submitted_to_traffic')<span class="pg-badge pg-badge-review">Submitted</span>@else<span class="pg-badge pg-badge-progress">Pending</span>@endif</td>
                                    <td class="text-right"><a href="{{ route('jobs.show', $job) }}" class="pg-btn-secondary">Open</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-slate-500">No assigned jobs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        @if($can('email_intake'))
            <section class="pg-card border-l-4 border-l-slate-950">
                <div class="pg-card-body">
                    <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Email command center</div><h3 class="mt-2 text-2xl font-black">Outlook / Email Intake Review</h3></div>
                        <a href="{{ route('email-intakes.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Open Email Intake →</a>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <a href="{{ route('email-intakes.index') }}" class="rounded-3xl bg-slate-950 p-5 text-white"><div class="text-sm font-bold text-slate-300">Pending email intake</div><div class="mt-3 text-5xl font-extrabold">{{ $pendingEmailIntakes }}</div></a>
                        <div class="rounded-3xl border border-slate-200 bg-white p-5"><div class="text-sm font-bold text-slate-500">Urgent jobs</div><div class="mt-3 text-5xl font-extrabold text-red-600">{{ $urgentJobs }}</div></div>
                        <a href="{{ route('jobs.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 hover:border-slate-400"><div class="text-sm font-bold text-slate-500">Visible active jobs</div><div class="mt-3 text-5xl font-extrabold">{{ $latestJobs->count() }}</div></a>
                    </div>
                </div>
            </section>
        @endif

        @if($canAny(['traffic_board', 'jobs', 'team_workload']))
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                @if($can('traffic_board'))
                    <div class="pg-card"><div class="pg-card-body"><div class="mb-6 flex items-center justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Traffic operations</div><h3 class="mt-2 text-xl font-bold">Traffic Board</h3></div><a href="{{ route('traffic.index') }}" class="text-sm font-semibold text-slate-600">View board →</a></div><div class="space-y-4">@forelse($workflowStages as $stage)<div class="flex justify-between rounded-2xl border border-slate-100 p-4"><span class="font-semibold">{{ $stage->name }}</span><span class="pg-badge pg-badge-new">{{ $stage->jobs_count }}</span></div>@empty<div class="text-sm text-slate-500">No workflow stages.</div>@endforelse</div></div></div>
                @endif
                @if($can('jobs'))
                    <div class="pg-card"><div class="pg-card-body"><div class="mb-6 flex items-center justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Jobs</div><h3 class="mt-2 text-xl font-bold">Latest Visible Jobs</h3></div><a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-slate-600">All jobs →</a></div><div class="space-y-3">@forelse($latestJobs as $job)<a href="{{ route('jobs.show', $job) }}" class="flex justify-between gap-4 border-b pb-3 last:border-b-0"><span><strong>{{ $job->job_number }}</strong><span class="block text-sm text-slate-500">{{ $job->title }}</span></span><span class="text-sm">{{ $job->currentWorkflowStage?->name ?? '-' }}</span></a>@empty<div class="text-slate-500">No jobs.</div>@endforelse</div></div></div>
                @endif
            </section>
        @endif

        @if($canAny(['team_workload', 'users', 'employee_leaves']))
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                @if($can('team_workload'))
                    <div class="pg-card"><div class="pg-card-body"><div class="mb-6 flex items-center justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Employee operations</div><h3 class="mt-2 text-xl font-bold">Team Workload</h3></div><a href="{{ route('workload.index') }}" class="text-sm font-semibold text-slate-600">View workload →</a></div><table class="w-full"><thead><tr class="border-b text-left"><th class="pb-3">Employee</th><th class="pb-3">Status</th><th class="pb-3">Jobs</th></tr></thead><tbody>@forelse($workloadUsers as $employee)@php($busy = (float) $employee->assigned_hours >= $employee->capacity_hours)@php($currentLeave = $employee->employeeLeaves->first())<tr class="border-b last:border-b-0"><td class="py-3">{{ $employee->name }}</td><td>@if($currentLeave)<span class="pg-badge pg-badge-review">On Leave</span>@else<span class="pg-badge {{ $busy ? 'pg-badge-progress' : 'pg-badge-completed' }}">{{ $busy ? 'Busy' : 'Available' }}</span>@endif</td><td>{{ $employee->active_jobs_count }}</td></tr>@empty<tr><td colspan="3" class="py-6 text-center text-slate-500">No team data.</td></tr>@endforelse</tbody></table></div></div>
                @endif
                @if($can('users') || $can('employee_leaves'))
                    <div class="pg-card"><div class="pg-card-body"><h3 class="mb-4 text-lg font-bold">{{ $can('users') ? 'Employee approvals' : 'My leave status' }}</h3><div class="grid gap-3 md:grid-cols-3">@if($can('users'))<a href="{{ route('admin.users.index') }}" class="rounded-2xl border border-blue-200 bg-blue-50 p-4"><div class="text-sm font-bold uppercase tracking-wide text-blue-700">Waiting approval</div><div class="mt-2 text-4xl font-extrabold text-blue-950">{{ $pendingEmployeeApprovals }}</div></a>@endif @if($can('employee_leaves'))<a href="{{ route('employee-leaves.index', ['status' => 'pending']) }}" class="rounded-2xl border border-amber-200 bg-amber-50 p-4"><div class="text-sm font-bold uppercase tracking-wide text-amber-700">Pending leaves</div><div class="mt-2 text-4xl font-extrabold text-amber-950">{{ $pendingLeaveRequests }}</div></a><a href="{{ route('employee-leaves.index', ['status' => 'approved']) }}" class="rounded-2xl border border-red-200 bg-red-50 p-4"><div class="text-sm font-bold uppercase tracking-wide text-red-700">On leave today</div><div class="mt-2 text-4xl font-extrabold text-red-950">{{ $employeesOnLeaveToday }}</div></a>@endif</div></div></div>
                @endif
            </section>
        @endif

        @if($canAny(['clients', 'client_requests', 'projects']))
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                @if($can('clients'))<a href="{{ route('admin.clients.index') }}" class="rounded-3xl border border-amber-200 bg-amber-50 p-5 hover:border-amber-400"><div class="text-sm font-bold uppercase tracking-wide text-amber-700">Client approvals</div><div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $pendingClientApprovals }}</div></a>@endif
                @if($can('client_requests'))<a href="{{ route('admin.client-requests.index') }}" class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 hover:border-emerald-400"><div class="text-sm font-bold uppercase tracking-wide text-emerald-700">New client requests</div><div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $newClientRequests }}</div></a>@endif
                @if($can('projects'))<div class="rounded-3xl border border-slate-200 bg-white p-5"><div class="text-sm font-bold uppercase tracking-wide text-slate-500">Clients / Projects</div><div class="mt-3 text-4xl font-extrabold">{{ $totalClients }} / {{ $totalProjects }}</div></div>@endif
            </section>
        @endif

        @if($can('events'))
            <section class="pg-card"><div class="pg-card-body"><div class="mb-5 flex items-center justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Calendar</div><h3 class="mt-2 text-xl font-bold">Upcoming events & campaign windows</h3></div><a href="{{ route('admin.events.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold">Events →</a></div><div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">@forelse($saudiCalendarEvents as $event)<div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['date'] }} · {{ $event['type'] }}</div><div class="mt-2 font-bold">{{ $event['title'] }}</div><div class="mt-1 text-sm text-slate-500">{{ $event['country'] }}</div></div>@empty<div class="text-sm text-slate-500">No upcoming calendar events.</div>@endforelse</div></div></section>
        @endif

        @if($can('website_pages'))
            <section class="pg-card"><div class="pg-card-body"><div class="mb-5 flex items-center justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Website management</div><h3 class="mt-2 text-lg font-bold">Website shortcuts</h3></div><a href="{{ route('admin.cms.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Manage pages</a></div><div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">@foreach ($websitePages as $websitePage)<div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ data_get($websitePage->sections, 'type', $websitePage->key) }}</div><div class="mt-1 text-lg font-bold">{{ $websitePage->title }}</div><div class="mt-4"><a href="{{ route('admin.cms.edit', $websitePage) }}" class="rounded-xl bg-white px-3 py-2 text-sm font-semibold shadow-sm">Edit</a></div></div>@endforeach</div></div></section>
        @endif

        @if($canAny(['crm', 'support', 'marketing', 'reports', 'archive', 'settings']))
            <section class="pg-card"><div class="pg-card-body"><h3 class="mb-5 text-lg font-bold">Other allowed tools</h3><div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">@if($can('crm'))<a href="{{ route('crm.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">CRM</a>@endif @if($can('support'))<a href="{{ route('support.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Support</a>@endif @if($can('marketing'))<a href="{{ route('marketing.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Marketing</a>@endif @if($can('reports'))<a href="{{ route('reports.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Reports</a>@endif @if($can('archive'))<a href="{{ route('archive.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Archive</a>@endif @if($can('settings'))<a href="{{ route('admin.settings.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Settings</a>@endif</div></div></section>
        @endif
    </div>
</x-app-layout>
