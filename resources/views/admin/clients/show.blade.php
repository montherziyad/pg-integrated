<x-app-layout>
    <x-slot name="header">Client Details</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $client->name }}</h2>
                <p class="pg-subtitle">{{ $client->client_code }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.clients.edit', $client->id) }}" class="pg-btn-primary">Edit</a>
                <a href="{{ route('admin.clients.index') }}" class="pg-btn-secondary">Back</a>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="pg-stat-label">Branch</div>
                    <div class="font-semibold">{{ $client->branch?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="pg-stat-label">Account Manager</div>
                    <div class="font-semibold">{{ $client->accountManager?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="pg-stat-label">Industry</div>
                    <div class="font-semibold">{{ $client->industry ?? '-' }}</div>
                </div>
                <div>
                    <div class="pg-stat-label">Email</div>
                    <div class="font-semibold">{{ $client->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="pg-stat-label">Phone</div>
                    <div class="font-semibold">{{ $client->phone ?? '-' }}</div>
                </div>
                <div>
                    <div class="pg-stat-label">Status</div>
                    <div class="font-semibold">{{ $client->is_active ? 'Active' : 'Inactive' }}</div>
                </div>
            </div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-lg font-bold mb-4">Client Projects</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-slate-500">
                                <th class="py-3">Project</th>
                                <th>Code</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->projects as $project)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-3">{{ $project->name }}</td>
                                    <td>{{ $project->project_code }}</td>
                                    <td>{{ $project->is_active ? 'Active' : 'Inactive' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-6 text-center text-slate-500">
                                        No projects for this client.
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
