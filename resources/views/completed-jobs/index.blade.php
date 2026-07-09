<x-app-layout>
    <x-slot name="header">Completed Jobs</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Completed Jobs</h2>
                <p class="pg-subtitle mt-1">Final deliveries that were approved by Client Service and published to the client portal.</p>
            </div>
            <a href="{{ route('deliveries.index') }}" class="pg-btn-secondary">Back to Delivery / Handover</a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('completed-jobs.index') }}" class="pg-card">
            <div class="pg-card-body grid gap-4 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <label class="block mb-2 font-semibold">Search</label>
                    <input name="q" value="{{ $search }}" placeholder="Job number, title, client, or project" class="w-full rounded-xl border-slate-300">
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Branch</label>
                    <select name="branch_id" class="w-full rounded-xl border-slate-300">
                        <option value="">All branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected((string) $branchId === (string) $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Client</label>
                    <select name="client_id" class="w-full rounded-xl border-slate-300">
                        <option value="">All clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected((string) $clientId === (string) $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Client Service</label>
                    <select name="client_service_id" class="w-full rounded-xl border-slate-300">
                        <option value="">All employees</option>
                        @foreach($clientServiceUsers as $user)
                            <option value="{{ $user->id }}" @selected((string) $clientServiceId === (string) $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3 lg:col-span-5">
                    <button class="pg-btn-primary">Search Completed Jobs</button>
                    <a href="{{ route('completed-jobs.index') }}" class="pg-btn-secondary">Clear</a>
                </div>
            </div>
        </form>

        <div class="pg-card">
            <div class="pg-card-body overflow-x-auto">
                <table class="w-full min-w-[1100px] text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3">Job</th>
                            <th>Client</th>
                            <th>Branch</th>
                            <th>Project</th>
                            <th>Client Service</th>
                            <th>Published</th>
                            <th>Delivery</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($jobs as $job)
                        @php
                            $clientServiceNames = $job->client?->clientServiceNames()
                                ?: ($job->responsibleUser?->name ?? $job->deliveryReviewer?->name ?? '-');
                        @endphp
                        <tr class="border-b last:border-b-0">
                            <td class="py-4">
                                <div class="font-semibold">{{ $job->job_number }}</div>
                                <div class="text-slate-500">{{ $job->title }}</div>
                            </td>
                            <td>{{ $job->client?->name ?? '-' }}</td>
                            <td>{{ $job->client?->branch?->name ?? '-' }}</td>
                            <td>{{ $job->project?->name ?? '-' }}</td>
                            <td>
                                <div class="space-y-1 font-semibold">
                                    @foreach(collect(explode(',', $clientServiceNames))->map(fn ($name) => trim($name))->filter() as $name)
                                        <div>{{ $name }}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $job->delivery_published_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>
                                @if($job->final_delivery_path)
                                    <a href="{{ $job->final_delivery_path }}" target="_blank" rel="noopener" class="font-bold text-blue-600">Open link</a>
                                @else
                                    <span class="text-slate-400">No link</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('jobs.show', $job) }}" class="pg-btn-secondary">Review / View</a>
                                    <form method="POST" action="{{ route('completed-jobs.reopen', $job) }}" onsubmit="return confirm('Reopen this completed job for follow-up?');">
                                        @csrf
                                        <button class="pg-btn-primary">Reopen</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-10 text-center text-slate-500">No completed jobs found.</td></tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-6">{{ $jobs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
