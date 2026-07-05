<x-dynamic-component component="client-portal.layout" :client="$client" title="Dashboard">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-bold uppercase tracking-[.2em] text-slate-500">Welcome back</p>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight">{{ $client->name }}</h1>
            <p class="mt-2 text-slate-500">{{ $client->company_name ?: 'Company profile is waiting for details.' }}</p>
        </div>
        <a href="{{ route('client.requests.create') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-center font-bold text-white">Request new brief / campaign</a>
    </div>

    <section class="mt-10 grid gap-4 sm:grid-cols-4">
        <div class="rounded-3xl bg-slate-950 p-6 text-white">
            <div class="text-sm text-slate-400">Projects</div>
            <div class="mt-3 text-4xl font-extrabold">{{ $projects->count() }}</div>
        </div>
        <div class="rounded-3xl bg-white p-6">
            <div class="text-sm text-slate-500">Active jobs</div>
            <div class="mt-3 text-4xl font-extrabold">{{ $jobs->where('is_archived', false)->count() }}</div>
        </div>
        <div class="rounded-3xl bg-amber-300 p-6">
            <div class="text-sm text-slate-700">Delivered</div>
            <div class="mt-3 text-4xl font-extrabold">{{ $jobs->whereNotNull('final_delivered_at')->count() }}</div>
        </div>
        <div class="rounded-3xl bg-white p-6">
            <div class="text-sm text-slate-500">Open requests</div>
            <div class="mt-3 text-4xl font-extrabold">{{ $projectRequests->where('status', '!=', 'completed')->count() }}</div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_.9fr]">
        <div class="rounded-3xl bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-extrabold">File status</h2>
                <a href="{{ route('client.profile') }}" class="text-sm font-bold text-slate-500">Update profile →</a>
            </div>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="text-xs font-bold uppercase tracking-wide text-slate-400">Contact</div>
                    <div class="mt-2 font-bold">{{ $client->contact_person ?: $client->name }}</div>
                    <div class="text-sm text-slate-500">{{ $client->email }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="text-xs font-bold uppercase tracking-wide text-slate-400">Country</div>
                    <div class="mt-2 font-bold">{{ $client->country ?: 'Not set' }}</div>
                    <div class="text-sm text-slate-500">{{ $client->city ?: 'City not set' }}</div>
                </div>
            </div>
            <p class="mt-5 text-sm leading-6 text-slate-500">{{ $client->company_profile ?: 'Add your company profile so PG Integrated can prepare stronger briefs and campaign recommendations.' }}</p>
        </div>

        <div class="rounded-3xl bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-extrabold">Upcoming calendar</h2>
                <a href="{{ route('client.calendar') }}" class="text-sm font-bold text-slate-500">Full calendar →</a>
            </div>
            <div class="mt-5 space-y-3">
                @foreach (array_slice($calendarEvents, 0, 4) as $event)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $event['date'] }} · {{ $event['country'] }}</div>
                        <div class="mt-1 font-bold">{{ $event['title'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mt-8 overflow-hidden rounded-3xl bg-white">
        <div class="flex items-center justify-between border-b border-slate-200 p-6">
            <h2 class="text-2xl font-extrabold">Job progress</h2>
            <a href="{{ route('client.projects') }}" class="text-sm font-bold text-slate-500">All projects →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr><th class="px-6 py-4">Job</th><th>Project</th><th>Stage</th><th>Progress</th><th>Due</th><th>Links</th></tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4"><div class="font-bold">{{ $job->title }}</div><div class="text-xs text-slate-500">{{ $job->job_number }}</div></td>
                            <td>{{ $job->project?->name ?? '—' }}</td>
                            <td>{{ $job->currentWorkflowStage?->name ?? 'Preparing' }}</td>
                            <td>{{ $job->completion_percentage }}%</td>
                            <td>{{ $job->final_due_at?->format('Y-m-d') ?? '—' }}</td>
                            <td>
                                @if ($job->final_delivery_path)
                                    <a href="{{ $job->final_delivery_path }}" class="font-bold text-blue-600" target="_blank">Delivery</a>
                                @else
                                    <span class="text-slate-400">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-slate-500">No jobs are available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-dynamic-component>
