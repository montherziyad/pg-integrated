<x-app-layout>
    <x-slot name="header">Projects Management</x-slot>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div><h2 class="pg-title">Projects Management</h2><p class="pg-subtitle mt-1">Manage client projects and project ownership.</p></div>
            <a href="{{ route('admin.projects.create') }}" class="pg-btn-primary">+ New Project</a>
        </div>
        @include('admin.partials.nav')
        @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>@endif
        <div class="pg-card"><div class="pg-card-body overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Project</th><th>Code</th><th>Client</th><th>Client Service</th><th>Project Manager</th><th>Dates</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($projects as $project)
                    <tr class="border-b last:border-b-0">
                        <td class="py-4 font-semibold">{{ $project->name }}</td><td>{{ $project->project_code }}</td>
                        <td>{{ $project->client?->name ?? '-' }}</td><td>{{ $project->client?->clientServiceNames() ?? '-' }}</td><td>{{ $project->projectManager?->name ?? '-' }}</td>
                        <td>{{ $project->start_date?->format('Y-m-d') ?? '-' }} — {{ $project->end_date?->format('Y-m-d') ?? '-' }}</td>
                        <td><span class="pg-badge {{ $project->is_active ? 'pg-badge-completed' : 'pg-badge-review' }}">{{ $project->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-right"><a href="{{ route('admin.projects.show', $project) }}" class="pg-btn-secondary">View</a></td>
                    </tr>
                @empty<tr><td colspan="8" class="py-10 text-center text-slate-500">No projects found.</td></tr>@endforelse
                </tbody>
            </table>
        </div></div>
    </div>
</x-app-layout>
