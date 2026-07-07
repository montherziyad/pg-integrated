<x-app-layout>
    <x-slot name="header">Categories Management</x-slot>
    <div class="space-y-6">
        <div class="flex justify-between items-center"><div><h2 class="pg-title">Categories Management</h2><p class="pg-subtitle mt-1">Manage job and creative service categories.</p></div><a href="{{ route('admin.categories.create') }}" class="pg-btn-primary">+ New Category</a></div>
        @include('admin.partials.nav')

        @include('admin.partials.search', ['action' => route('admin.categories.index'), 'placeholder' => 'Search categories by name, code, parent, or default team...'])
        @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>@endif
        <div class="pg-card"><div class="pg-card-body overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Name</th><th>Code</th><th>Parent</th><th>Default Team</th><th>Jobs</th><th>Status</th><th></th></tr></thead>
            <tbody>@forelse($categories as $category)<tr class="border-b last:border-b-0"><td class="py-4 font-semibold">{{ $category->parent_id ? '↳ ' : '' }}{{ $category->name }}</td><td>{{ $category->code }}</td><td>{{ $category->parent?->name ?? 'Main' }}</td><td>{{ $category->default_team ?? '-' }}</td><td>{{ $category->jobs_count + $category->descendant_jobs_count }}</td><td><span class="pg-badge {{ $category->is_active ? 'pg-badge-completed' : 'pg-badge-review' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td><td class="text-right"><a href="{{ route('admin.categories.show', $category) }}" class="pg-btn-secondary">View</a></td></tr>@empty<tr><td colspan="7" class="py-10 text-center text-slate-500">No categories found.</td></tr>@endforelse</tbody>
        </table></div></div>
    </div>
</x-app-layout>
