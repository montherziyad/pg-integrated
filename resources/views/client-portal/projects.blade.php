<x-dynamic-component component="client-portal.layout" :client="$client" title="Projects">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-4xl font-extrabold">Projects & requests</h1>
            <p class="mt-2 text-slate-500">Follow current projects, previous projects, job links, WeTransfer/final delivery links, and submitted requests.</p>
        </div>
        <a href="{{ route('client.requests.create') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-center font-bold text-white">Request new project</a>
    </div>

    <section class="mt-8 grid gap-5 md:grid-cols-2">
        @forelse ($projects as $project)
            <article class="rounded-3xl bg-white p-6">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $project->project_code }}</div>
                <h2 class="mt-2 text-2xl font-extrabold">{{ $project->name }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">{{ $project->description ?: 'No description yet.' }}</p>
                <div class="mt-4 flex flex-wrap gap-2 text-xs font-bold">
                    <span class="rounded-full bg-slate-100 px-3 py-1">{{ $project->jobs_count }} jobs</span>
                    <span class="rounded-full {{ $project->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }} px-3 py-1">{{ $project->is_active ? 'Current' : 'Previous' }}</span>
                </div>
            </article>
        @empty
            <div class="rounded-3xl bg-white p-6 text-slate-500">No projects yet.</div>
        @endforelse
    </section>

    <section class="mt-8 overflow-hidden rounded-3xl bg-white">
        <div class="border-b border-slate-200 p-6"><h2 class="text-2xl font-extrabold">Jobs and delivery links</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr><th class="px-6 py-4">Job</th><th>Project</th><th>Job Responsible</th><th>Progress</th><th>Due</th><th>Brief / Dropbox</th><th>Final / WeTransfer</th></tr>
                </thead>
                <tbody>
                @forelse ($jobs as $job)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4"><div class="font-bold">{{ $job->title }}</div><div class="text-xs text-slate-500">{{ $job->job_number }}</div></td>
                        <td>{{ $job->project?->name ?? '—' }}</td>
                        <td>
                            <div class="font-bold">{{ $job->responsibleUser?->name ?? 'Not assigned' }}</div>
                            <div class="text-xs text-slate-500">Final delivery owner</div>
                        </td>
                        <td>{{ $job->completion_percentage }}%</td>
                        <td>{{ $job->final_due_at?->format('Y-m-d') ?? '—' }}</td>
                        <td>
                            @if ($job->dropbox_folder_path)
                                <a href="{{ $job->dropbox_folder_path }}" target="_blank" class="font-bold text-blue-600">Open</a>
                            @else
                                <span class="text-slate-400">Not added</span>
                            @endif
                        </td>
                        <td>
                            @if ($job->final_delivery_path && $job->delivery_review_status === 'published')
                                <a href="{{ $job->final_delivery_path }}" target="_blank" class="font-bold text-blue-600">Open</a>
                            @else
                                <span class="text-slate-400">Pending</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-8 text-slate-500">No jobs yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="mt-8 rounded-3xl bg-white p-6">
        <h2 class="text-2xl font-extrabold">Submitted requests</h2>
        <div class="mt-5 space-y-3">
            @forelse ($projectRequests as $request)
                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $request->request_number }} · {{ $request->type }}</div>
                            <div class="mt-1 font-bold">{{ $request->title }}</div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold">{{ $request->status }}</span>
                    </div>
                </div>
            @empty
                <p class="text-slate-500">No requests submitted yet.</p>
            @endforelse
        </div>
    </section>
</x-dynamic-component>
