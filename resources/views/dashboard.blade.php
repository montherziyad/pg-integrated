<x-app-layout>

    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">

        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Welcome back, {{ Auth::user()->name }}</h2>
                <p class="pg-subtitle mt-1">PG Integrated Creative Operations Dashboard</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('email-intakes.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Review emails</a>
                <a href="{{ route('traffic.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold">Traffic board</a>
                <a href="{{ route('admin.client-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold">Client requests</a>
            </div>
        </div>

        <!-- 1. Emails first -->
        <section class="pg-card border-l-4 border-l-slate-950">
            <div class="pg-card-body">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">01 · Email command center</div>
                        <h3 class="mt-2 text-2xl font-black">Outlook / Email Intake Review</h3>
                        <p class="mt-1 text-sm text-slate-500">Start here: incoming traffic emails, briefs, attachments, and validation.</p>
                    </div>
                    <a href="{{ route('email-intakes.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Open Email Intake →</a>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <a href="{{ route('email-intakes.index') }}" class="rounded-3xl bg-slate-950 p-5 text-white">
                        <div class="text-sm font-bold text-slate-300">Pending email intake</div>
                        <div class="mt-3 text-5xl font-extrabold">{{ $pendingEmailIntakes }}</div>
                        <div class="mt-2 text-sm text-slate-300">Validated emails waiting review</div>
                    </a>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5">
                        <div class="text-sm font-bold text-slate-500">Urgent jobs from traffic</div>
                        <div class="mt-3 text-5xl font-extrabold text-red-600">{{ $urgentJobs }}</div>
                        <div class="mt-2 text-sm text-slate-500">Needs fast operational attention</div>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 hover:border-slate-400">
                        <div class="text-sm font-bold text-slate-500">Total active jobs</div>
                        <div class="mt-3 text-5xl font-extrabold">{{ $totalJobs }}</div>
                        <div class="mt-2 text-sm text-slate-500">Across production and studio workflow</div>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. Traffic operations -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">02 · Traffic operations</div>
                            <h3 class="mt-2 text-xl font-bold">Traffic Board</h3>
                        </div>
                        <a href="{{ route('traffic.index') }}" class="text-sm font-semibold text-slate-600">View board →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($workflowStages as $stage)
                            <div class="flex justify-between rounded-2xl border border-slate-100 p-4"><span class="font-semibold">{{ $stage->name }}</span><span class="pg-badge pg-badge-new">{{ $stage->jobs_count }}</span></div>
                        @empty
                            <div class="text-sm text-slate-500">No workflow stages.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Jobs</div>
                            <h3 class="mt-2 text-xl font-bold">Latest Jobs</h3>
                        </div>
                        <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-slate-600">All jobs →</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($latestJobs as $job)
                            <a href="{{ route('jobs.show', $job) }}" class="flex justify-between gap-4 border-b pb-3 last:border-b-0"><span><strong>{{ $job->job_number }}</strong><span class="block text-sm text-slate-500">{{ $job->title }}</span></span><span class="text-sm">{{ $job->currentWorkflowStage?->name ?? '-' }}</span></a>
                        @empty
                            <div class="text-slate-500">No jobs.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Employee operations -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">03 · Employee operations</div>
                            <h3 class="mt-2 text-xl font-bold">Team Workload</h3>
                        </div>
                        <a href="{{ route('workload.index') }}" class="text-sm font-semibold text-slate-600">View workload →</a>
                    </div>
                    <table class="w-full">
                        <thead><tr class="border-b text-left"><th class="pb-3">Employee</th><th class="pb-3">Status</th><th class="pb-3">Jobs</th></tr></thead>
                        <tbody>
                            @forelse($workloadUsers as $user)
                                @php($busy = (float) $user->assigned_hours >= $user->capacity_hours)
                                <tr class="border-b last:border-b-0"><td class="py-3">{{ $user->name }}</td><td><span class="pg-badge {{ $busy ? 'pg-badge-progress' : 'pg-badge-completed' }}">{{ $busy ? 'Busy' : 'Available' }}</span></td><td>{{ $user->active_jobs_count }}</td></tr>
                            @empty<tr><td colspan="3" class="py-6 text-center text-slate-500">No team data.</td></tr>@endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">Pending employee approvals</h3><a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-600">Open →</a></div>
                    <div class="mb-4 rounded-2xl border border-blue-200 bg-blue-50 p-4"><div class="text-sm font-bold uppercase tracking-wide text-blue-700">Waiting approval</div><div class="mt-2 text-4xl font-extrabold text-blue-950">{{ $pendingEmployeeApprovals }}</div></div>
                    <div class="space-y-3">
                        @forelse($pendingEmployees as $employee)
                            <a href="{{ route('admin.users.edit', $employee) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50"><div class="font-bold">{{ $employee->name }}</div><div class="mt-1 text-sm text-slate-500">{{ $employee->email }}</div></a>
                        @empty
                            <div class="text-sm text-slate-500">No pending employee approvals.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Client operations -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <a href="{{ route('admin.clients.index') }}" class="rounded-3xl border border-amber-200 bg-amber-50 p-5 hover:border-amber-400"><div class="text-sm font-bold uppercase tracking-wide text-amber-700">Client approvals</div><div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $pendingClientApprovals }}</div><div class="mt-1 text-sm text-amber-800">Waiting activation or portal access</div></a>
            <a href="{{ route('admin.client-requests.index') }}" class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 hover:border-emerald-400"><div class="text-sm font-bold uppercase tracking-wide text-emerald-700">New client requests</div><div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $newClientRequests }}</div><div class="mt-1 text-sm text-emerald-800">Briefs, quotations, and project requests</div></a>
            <div class="rounded-3xl border border-slate-200 bg-white p-5"><div class="text-sm font-bold uppercase tracking-wide text-slate-500">Clients / Projects</div><div class="mt-3 text-4xl font-extrabold">{{ $totalClients }} / {{ $totalProjects }}</div><div class="mt-1 text-sm text-slate-500">Registered clients and active projects</div></div>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="pg-card"><div class="pg-card-body"><div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">Pending client approvals</h3><a href="{{ route('admin.clients.index') }}" class="text-sm font-semibold text-slate-600">Open →</a></div><div class="space-y-3">@forelse($pendingClients as $client)<a href="{{ route('admin.clients.edit', $client) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50"><div class="font-bold">{{ $client->name }}</div><div class="mt-1 text-sm text-slate-500">{{ $client->email ?: 'No email' }} · {{ $client->company_name ?: 'No company profile' }}</div></a>@empty<div class="text-sm text-slate-500">No pending client approvals.</div>@endforelse</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">New client requests</h3><a href="{{ route('admin.client-requests.index') }}" class="text-sm font-semibold text-slate-600">Open →</a></div><div class="space-y-3">@forelse($latestClientRequests as $request)<a href="{{ route('admin.client-requests.show', $request) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50"><div class="font-bold">{{ $request->title }}</div><div class="mt-1 text-sm text-slate-500">{{ $request->client?->name ?? 'Client' }} · {{ $request->service_name ?? $request->type }}</div></a>@empty<div class="text-sm text-slate-500">No new client requests.</div>@endforelse</div></div></div>
        </section>

        <!-- 5. Calendar snapshot -->
        <section class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">Saudi local calendar</div>
                        <h3 class="mt-2 text-xl font-bold">Upcoming Saudi events & campaign windows</h3>
                        <p class="mt-1 text-sm text-slate-500">Short planning view synced with the platform calendar for Saudi market moments.</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-800">Saudi Arabia</span>
                </div>
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($saudiCalendarEvents as $event)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['date'] }} · {{ $event['type'] }}</div><div class="mt-2 font-bold">{{ $event['title'] }}</div><div class="mt-1 text-sm text-slate-500">{{ $event['country'] }}</div></div>
                    @empty
                        <div class="text-sm text-slate-500">No upcoming calendar events.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- 6. Website pages -->
        <section class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em] text-slate-400">05 · Website management</div><h3 class="mt-2 text-lg font-bold">Website shortcuts</h3><p class="mt-1 text-sm text-slate-500">Quick access to website sections and page editors.</p></div><div class="flex flex-wrap gap-2"><a href="{{ route('admin.cms.index') }}" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Manage all pages</a><a href="{{ route('website.home') }}" target="_blank" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold">Open website</a></div></div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($websitePages as $websitePage)
                        @php
                            $type = data_get($websitePage->sections, 'type', $websitePage->key);
                            $publicUrl = match ($websitePage->key) {'home' => route('website.home'), 'about', 'services', 'work', 'team', 'clients', 'contact' => url($websitePage->slug), default => route('website.page', $websitePage->slug)};
                            $label = match ($websitePage->key) {'work' => 'Projects & portfolio', 'team' => 'Team members', 'services' => 'Services content', 'clients' => 'Client logos', 'contact' => 'Contact details', 'about' => 'About content', default => 'Homepage sections'};
                        @endphp
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="flex items-start justify-between gap-3"><div><div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $type }}</div><div class="mt-1 text-lg font-bold">{{ $websitePage->title }}</div><div class="mt-1 text-sm text-slate-500">{{ $label }}</div></div><span class="rounded-full px-2 py-1 text-xs font-semibold {{ $websitePage->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">{{ $websitePage->is_published ? 'Live' : 'Draft' }}</span></div><div class="mt-4 flex gap-2"><a href="{{ route('admin.cms.edit', $websitePage) }}" class="flex-1 rounded-xl bg-white px-3 py-2 text-center text-sm font-semibold shadow-sm">Edit</a><a href="{{ $publicUrl }}" target="_blank" class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-center text-sm font-semibold">View</a></div></div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 7. Logs and other tools -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="pg-card"><div class="pg-card-body"><h3 class="mb-5 text-lg font-bold">Latest Activities / Log</h3><div class="space-y-3">@forelse($latestActivities as $activity)<div class="border-b pb-3 last:border-b-0"><div class="font-semibold">{{ $activity->description ?? $activity->activity_type }}</div><div class="text-sm text-slate-500">{{ $activity->user?->name ?? 'System' }} · {{ $activity->activity_at?->diffForHumans() }}</div></div>@empty<div class="text-slate-500">No activities.</div>@endforelse</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><h3 class="mb-5 text-lg font-bold">Other tools</h3><div class="grid gap-3 sm:grid-cols-2"><a href="{{ route('crm.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">CRM</a><a href="{{ route('support.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Support</a><a href="{{ route('marketing.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Marketing</a><a href="{{ route('reports.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Reports</a><a href="{{ route('archive.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Archive</a><a href="{{ route('admin.settings.index') }}" class="rounded-2xl border border-slate-200 p-4 font-bold hover:bg-slate-50">Settings</a></div></div></div>
        </section>

    </div>

</x-app-layout>
