<x-app-layout>
<x-slot name="header">{{ $company->name }}</x-slot>
<div class="grid lg:grid-cols-3 gap-6">
<div class="lg:col-span-2 space-y-6">
<div class="bg-white rounded-xl shadow p-6"><div class="flex justify-between"><h2 class="text-2xl font-bold">{{ $company->name }}</h2><a href="{{ route('crm.edit',$company) }}" class="text-blue-600">Edit</a></div><p class="text-slate-500">{{ $company->industry }} · {{ $company->country }}</p><div class="mt-4 grid md:grid-cols-4 gap-3"><div>Stage<br><b>{{ $company->status }}</b></div><div>Score<br><b>{{ $company->lead_score }}%</b></div><div>Value<br><b>{{ $company->expected_value }}</b></div><div>Owner<br><b>{{ $company->owner?->name ?? '-' }}</b></div></div></div>
<div class="bg-white rounded-xl shadow p-6"><h3 class="font-bold mb-3">Timeline</h3>@foreach($company->activities as $activity)<div class="border-l-2 pl-4 pb-4"><b>{{ $activity->summary }}</b><div class="text-xs text-slate-500">{{ $activity->type }} · {{ $activity->channel }} · {{ $activity->created_at->format('Y-m-d H:i') }}</div><p>{{ $activity->body }}</p></div>@endforeach</div>
</div>
<div class="space-y-6">
<form method="POST" action="{{ route('crm.activities.store',$company) }}" class="bg-white rounded-xl shadow p-6 space-y-3">@csrf<h3 class="font-bold">Log Activity</h3><input name="type" value="follow_up" class="w-full rounded border-slate-300"><input name="channel" placeholder="email/phone/whatsapp" class="w-full rounded border-slate-300"><input name="summary" placeholder="Summary" class="w-full rounded border-slate-300"><textarea name="body" class="w-full rounded border-slate-300"></textarea><button class="px-4 py-2 bg-slate-900 text-white rounded">Add</button></form>
<form method="POST" action="{{ route('crm.tasks.store',$company) }}" class="bg-white rounded-xl shadow p-6 space-y-3">@csrf<h3 class="font-bold">Create Task</h3><input name="title" placeholder="Task" class="w-full rounded border-slate-300"><textarea name="description" class="w-full rounded border-slate-300"></textarea><input type="datetime-local" name="due_at" class="w-full rounded border-slate-300"><button class="px-4 py-2 bg-slate-900 text-white rounded">Assign</button></form>
</div>
</div></x-app-layout>
