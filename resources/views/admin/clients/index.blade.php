<x-app-layout>
    <x-slot name="header">Clients Management</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Clients Management</h2>
                <p class="pg-subtitle mt-1">Manage client accounts and account ownership.</p>
            </div>

            <a href="{{ route('admin.clients.create') }}" class="pg-btn-primary">
                + New Client
            </a>
        </div>

        @include('admin.partials.nav')

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="pg-card">
            <div class="pg-card-body">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-slate-500">
                                <th class="py-3">Client</th>
                                <th>Code</th>
                                <th>Branch</th>
                                <th>Account Manager</th>
                                <th>Projects</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($clients as $client)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-4 font-semibold">{{ $client->name }}</td>
                                    <td>{{ $client->client_code }}</td>
                                    <td>{{ $client->branch?->name ?? '-' }}</td>
                                    <td>{{ $client->accountManager?->name ?? '-' }}</td>
                                    <td>{{ $client->projects_count }}</td>
                                    <td>
                                        @if($client->is_active)
                                            <span class="pg-badge pg-badge-completed">Active</span>
                                        @else
                                            <span class="pg-badge pg-badge-review">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.clients.show', $client->id) }}" class="pg-btn-secondary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-slate-500">
                                        No clients found.
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
