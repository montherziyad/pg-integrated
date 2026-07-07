<x-app-layout>
    <x-slot name="header">Teams Management</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Teams Management</h2>
                <p class="pg-subtitle mt-1">Manage company departments and teams.</p>
            </div>

            <a href="{{ route('admin.teams.create') }}" class="pg-btn-primary">
                + New Team
            </a>
        </div>

        @include('admin.partials.nav')

        @include('admin.partials.search', ['action' => route('admin.teams.index'), 'placeholder' => 'Search teams by name, code, or description...'])

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="pg-card">
            <div class="pg-card-body">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3">Name</th>
                            <th>Code</th>
                            <th>Users</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($teams as $team)
                            <tr class="border-b last:border-b-0">
                                <td class="py-4 font-semibold">{{ $team->name }}</td>
                                <td>{{ $team->code }}</td>
                                <td>{{ $team->users_count }}</td>
                                <td>
                                    @if($team->is_active)
                                        <span class="pg-badge pg-badge-completed">Active</span>
                                    @else
                                        <span class="pg-badge pg-badge-review">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.teams.show', $team->id) }}" class="pg-btn-secondary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-500">
                                    No teams found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>