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


        <!-- Action Center / Notifications -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.clients.index') }}" class="rounded-3xl border border-amber-200 bg-amber-50 p-5 hover:border-amber-400">
                <div class="text-sm font-bold uppercase tracking-wide text-amber-700">Client approvals</div>
                <div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $pendingClientApprovals }}</div>
                <div class="mt-1 text-sm text-amber-800">Clients waiting activation or portal access</div>
            </a>
            <a href="{{ route('admin.users.index') }}" class="rounded-3xl border border-blue-200 bg-blue-50 p-5 hover:border-blue-400">
                <div class="text-sm font-bold uppercase tracking-wide text-blue-700">Employee approvals</div>
                <div class="mt-3 text-4xl font-extrabold text-blue-950">{{ $pendingEmployeeApprovals }}</div>
                <div class="mt-1 text-sm text-blue-800">Employees waiting admin approval</div>
            </a>
            <a href="{{ route('admin.client-requests.index') }}" class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 hover:border-emerald-400">
                <div class="text-sm font-bold uppercase tracking-wide text-emerald-700">New client requests</div>
                <div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $newClientRequests }}</div>
                <div class="mt-1 text-sm text-emerald-800">Briefs, quotations, and project requests</div>
            </a>
            <a href="{{ route('email-intakes.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 hover:border-slate-400">
                <div class="text-sm font-bold uppercase tracking-wide text-slate-500">Email intake</div>
                <div class="mt-3 text-4xl font-extrabold">{{ $pendingEmailIntakes }}</div>
                <div class="mt-1 text-sm text-slate-500">New traffic emails waiting review</div>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">Pending client approvals</h3><a href="{{ route('admin.clients.index') }}" class="text-sm font-semibold text-slate-600">Open →</a></div>
                    <div class="space-y-3">
                        @forelse($pendingClients as $client)
                            <a href="{{ route('admin.clients.edit', $client) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50">
                                <div class="font-bold">{{ $client->name }}</div>
                                <div class="mt-1 text-sm text-slate-500">{{ $client->email ?: 'No email' }} · {{ $client->company_name ?: 'No company profile' }}</div>
                                <div class="mt-2 text-xs font-bold uppercase tracking-wide text-amber-700">Needs activation / portal approval</div>
                            </a>
                        @empty
                            <div class="text-sm text-slate-500">No pending client approvals.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">Pending employee approvals</h3><a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-600">Open →</a></div>
                    <div class="space-y-3">
                        @forelse($pendingEmployees as $employee)
                            <a href="{{ route('admin.users.edit', $employee) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50">
                                <div class="font-bold">{{ $employee->name }}</div>
                                <div class="mt-1 text-sm text-slate-500">{{ $employee->email }}</div>
                                <div class="mt-2 text-xs font-bold uppercase tracking-wide text-blue-700">Waiting admin approval</div>
                            </a>
                        @empty
                            <div class="text-sm text-slate-500">No pending employee approvals.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">New client requests</h3><a href="{{ route('admin.client-requests.index') }}" class="text-sm font-semibold text-slate-600">Open →</a></div>
                    <div class="space-y-3">
                        @forelse($latestClientRequests as $request)
                            <a href="{{ route('admin.client-requests.show', $request) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50">
                                <div class="font-bold">{{ $request->title }}</div>
                                <div class="mt-1 text-sm text-slate-500">{{ $request->client?->name ?? 'Client' }} · {{ $request->service_name ?? $request->type }}</div>
                                <div class="mt-2 text-xs font-bold uppercase tracking-wide text-emerald-700">New request</div>
                            </a>
                        @empty
                            <div class="text-sm text-slate-500">No new client requests.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Website Shortcuts -->
        <div class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Website shortcuts</h3>
                        <p class="mt-1 text-sm text-slate-500">Quick access to the latest website sections and page editors.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.cms.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Manage all pages</a>
                        <a href="{{ route('website.home') }}" target="_blank" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold">Open website</a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($websitePages as $websitePage)
                        @php
                            $type = data_get($websitePage->sections, 'type', $websitePage->key);
                            $publicUrl = match ($websitePage->key) {
                                'home' => route('website.home'),
                                'about', 'services', 'work', 'team', 'clients', 'contact' => url($websitePage->slug),
                                default => route('website.page', $websitePage->slug),
                            };
                            $label = match ($websitePage->key) {
                                'work' => 'Projects & portfolio',
                                'team' => 'Team members',
                                'services' => 'Services content',
                                'clients' => 'Client logos',
                                'contact' => 'Contact details',
                                'about' => 'About content',
                                default => 'Homepage sections',
                            };
                        @endphp
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $type }}</div>
                                    <div class="mt-1 text-lg font-bold">{{ $websitePage->title }}</div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $label }}</div>
                                </div>
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $websitePage->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $websitePage->is_published ? 'Live' : 'Draft' }}
                                </span>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('admin.cms.edit', $websitePage) }}" class="flex-1 rounded-xl bg-white px-3 py-2 text-center text-sm font-semibold shadow-sm">Edit</a>
                                <a href="{{ $publicUrl }}" target="_blank" class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-center text-sm font-semibold">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    بعد إرسال كريدنشين المشاريع، سأضيف المشاريع إلى صفحة Work وأوزع جزءاً من المحتوى على About وServices وClients حسب ما يناسب كل صفحة.
                </div>
            </div>
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
