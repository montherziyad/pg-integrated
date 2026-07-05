<x-app-layout><x-slot name="header">Create Campaign</x-slot>
<form method="POST" action="{{ route('marketing.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">@csrf
<div class="grid md:grid-cols-3 gap-4"><input name="name" placeholder="Campaign name" class="rounded border-slate-300"><select name="channel" class="rounded border-slate-300"><option>email</option><option>whatsapp</option><option>sms</option><option>linkedin</option></select><select name="status" class="rounded border-slate-300"><option>draft</option><option>scheduled</option><option>active</option></select></div>
<textarea name="message_template" rows="10" class="w-full rounded border-slate-300" placeholder="Message template..."></textarea><input type="datetime-local" name="scheduled_at" class="rounded border-slate-300"><button class="px-5 py-2 bg-slate-900 text-white rounded-lg">Save Campaign</button>
</form></x-app-layout>
