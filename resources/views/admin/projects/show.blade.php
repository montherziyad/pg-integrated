<x-app-layout><x-slot name="header">Project Details</x-slot><div class="space-y-6">
<div class="flex justify-between"><div><h2 class="pg-title">{{ $project->name }}</h2><p class="pg-subtitle">{{ $project->project_code }}</p></div><div class="flex gap-3"><a href="{{ route('admin.projects.edit', $project) }}" class="pg-btn-primary">Edit</a><a href="{{ route('admin.projects.index') }}" class="pg-btn-secondary">Back</a></div></div>
<div class="pg-card"><div class="pg-card-body grid grid-cols-1 md:grid-cols-3 gap-6">
<div><div class="pg-stat-label">Client</div><div class="font-semibold">{{ $project->client?->name ?? '-' }}</div></div>
<div><div class="pg-stat-label">Manager</div><div class="font-semibold">{{ $project->projectManager?->name ?? '-' }}</div></div>
<div><div class="pg-stat-label">Status</div><div class="font-semibold">{{ $project->is_active ? 'Active' : 'Inactive' }}</div></div>
<div><div class="pg-stat-label">Start Date</div><div class="font-semibold">{{ $project->start_date?->format('Y-m-d') ?? '-' }}</div></div>
<div><div class="pg-stat-label">End Date</div><div class="font-semibold">{{ $project->end_date?->format('Y-m-d') ?? '-' }}</div></div>
<div class="md:col-span-3"><div class="pg-stat-label">Description</div><div class="font-semibold">{{ $project->description ?? '-' }}</div></div>
</div></div></div></x-app-layout>
