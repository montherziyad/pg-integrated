<x-app-layout>
    <x-slot name="header">Branches Management</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Branches Management</h2>
                <p class="pg-subtitle mt-1">Manage company locations and operating branches.</p>
            </div>

            <a href="{{ route('admin.branches.create') }}" class="pg-btn-primary">
                + New Branch
            </a>
        </div>

        @include('admin.partials.nav')

        @include('admin.partials.search', ['action' => route('admin.branches.index'), 'placeholder' => 'Search branches by name, code, city, or country...'])

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
                                <th class="py-3">Name</th>
                                <th>Code</th>
                                <th>Location</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($branches as $branch)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-4 font-semibold">{{ $branch->name }}</td>
                                    <td>{{ $branch->code }}</td>
                                    <td>
                                        {{ collect([$branch->city, $branch->country])->filter()->join(', ') ?: '-' }}
                                    </td>
                                    <td>{{ $branch->users_count }}</td>
                                    <td>
                                        @if($branch->is_active)
                                            <span class="pg-badge pg-badge-completed">Active</span>
                                        @else
                                            <span class="pg-badge pg-badge-review">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.branches.show', $branch->id) }}" class="pg-btn-secondary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-500">
                                        No branches found.
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
