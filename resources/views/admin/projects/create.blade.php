<x-app-layout><x-slot name="header">Create Project</x-slot><div class="space-y-6">
<div class="flex justify-between"><div><h2 class="pg-title">Create Project</h2><p class="pg-subtitle">Add a new client project.</p></div><a href="{{ route('admin.projects.index') }}" class="pg-btn-secondary">← Back</a></div>
<div class="pg-card"><div class="pg-card-body">@if($errors->any())<div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-8">@csrf @include('admin.projects.form')<div class="flex justify-end gap-3"><a href="{{ route('admin.projects.index') }}" class="pg-btn-secondary">Cancel</a><button class="pg-btn-primary">Save Project</button></div></form>
</div></div></div></x-app-layout>
