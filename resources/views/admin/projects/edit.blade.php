<x-app-layout><x-slot name="header">Edit Project</x-slot><div class="space-y-6">
<div class="flex justify-between"><div><h2 class="pg-title">Edit Project</h2><p class="pg-subtitle">{{ $project->name }}</p></div><a href="{{ route('admin.projects.show', $project) }}" class="pg-btn-secondary">← Back</a></div>
<div class="pg-card"><div class="pg-card-body">@if($errors->any())<div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.projects.update', $project) }}" class="space-y-8">@csrf @method('PUT') @include('admin.projects.form')<div class="flex justify-end gap-3"><a href="{{ route('admin.projects.show', $project) }}" class="pg-btn-secondary">Cancel</a><button class="pg-btn-primary">Update Project</button></div></form>
</div></div></div></x-app-layout>
