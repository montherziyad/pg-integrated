<x-app-layout><x-slot name="header">Create Ticket</x-slot>
<form method="POST" action="{{ route('support.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">@csrf
<input name="subject" placeholder="Subject" class="w-full rounded border-slate-300">
<div class="grid md:grid-cols-3 gap-4"><select name="priority" class="rounded border-slate-300"><option>normal</option><option>high</option><option>urgent</option></select><select name="channel" class="rounded border-slate-300"><option>website</option><option>email</option><option>whatsapp</option><option>phone</option></select><select name="assigned_to" class="rounded border-slate-300"><option value="">Assign to...</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
<textarea name="message" rows="6" placeholder="Initial message" class="w-full rounded border-slate-300"></textarea>
<button class="px-5 py-2 bg-slate-900 text-white rounded-lg">Create</button>
</form></x-app-layout>
