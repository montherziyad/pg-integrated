<x-app-layout>
    <x-slot name="header">Delivery / Handover</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Delivery / Handover</h2>
                <p class="pg-subtitle mt-1">Search for a job, review delivery links, document what was delivered, then publish approved work to Completed Jobs.</p>
            </div>
            <a href="{{ route('completed-jobs.index') }}" class="pg-btn-secondary">Open Completed Jobs</a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('deliveries.index') }}" class="pg-card">
            <div class="pg-card-body">
                <label class="block mb-2 font-semibold">Search job by job number, title, client, or project</label>
                <div class="flex flex-col gap-3 md:flex-row">
                    <input name="q" value="{{ $search }}" placeholder="Example: PG-DEMO-JOB-001 or Brand Launch Campaign" class="w-full rounded-xl border-slate-300">
                    <button class="pg-btn-primary whitespace-nowrap">Search</button>
                </div>
            </div>
        </form>

        <div class="pg-card">
            <div class="pg-card-body overflow-x-auto">
                <table class="w-full min-w-[980px] text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3">Job</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Stage</th>
                            <th>Team Due</th>
                            <th>Progress</th>
                            <th>Handover</th>
                            <th>Delivery</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($jobs as $job)
                        <tr class="border-b last:border-b-0">
                            <td class="py-4">
                                <div class="font-semibold">{{ $job->job_number }}</div>
                                <div class="text-slate-500">{{ $job->title }}</div>
                            </td>
                            <td>{{ $job->client?->name ?? '-' }}</td>
                            <td>{{ $job->project?->name ?? '-' }}</td>
                            <td>{{ $job->currentWorkflowStage?->name ?? 'Preparing' }}</td>
                            <td>{{ $job->production_due_at?->format('Y-m-d H:i') ?? 'Waiting' }}</td>
                            <td>{{ $job->completion_percentage }}%</td>
                            <td>
                                @if($job->employee_handover_status === 'submitted_to_traffic')
                                    <span class="pg-badge pg-badge-review">Submitted</span>
                                @else
                                    <span class="pg-badge pg-badge-progress">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($job->final_delivery_path)
                                    <a href="{{ $job->final_delivery_path }}" target="_blank" class="font-bold text-blue-600">Delivery link</a>
                                @else
                                    <span class="text-slate-400">Pending</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('deliveries.edit', $job) }}" class="pg-btn-primary">Open Handover</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="py-10 text-center text-slate-500">No active jobs found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-6">{{ $jobs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
