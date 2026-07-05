<x-app-layout>
<x-slot name="header">CRM Company</x-slot>
<form method="POST" action="{{ $company->exists ? route('crm.update',$company) : route('crm.store') }}" class="bg-white rounded-xl shadow p-6 space-y-5">@csrf @if($company->exists) @method('PUT') @endif
<div class="grid md:grid-cols-3 gap-4">
<label>Name <input name="name" value="{{ old('name',$company->name) }}" class="w-full rounded border-slate-300"></label>
<label>Industry <input name="industry" value="{{ old('industry',$company->industry) }}" class="w-full rounded border-slate-300"></label>
<label>Country <input name="country" value="{{ old('country',$company->country) }}" class="w-full rounded border-slate-300"></label>
<label>Website <input name="website" value="{{ old('website',$company->website) }}" class="w-full rounded border-slate-300"></label>
<label>LinkedIn <input name="linkedin_url" value="{{ old('linkedin_url',$company->linkedin_url) }}" class="w-full rounded border-slate-300"></label>
<label>Source <input name="source" value="{{ old('source',$company->source) }}" class="w-full rounded border-slate-300"></label>
<label>Status <select name="status" class="w-full rounded border-slate-300">@foreach(['new','contacted','interested','proposal','negotiation','won','lost'] as $s)<option @selected(old('status',$company->status ?: 'new')===$s)>{{ $s }}</option>@endforeach</select></label>
<label>Lead Score <input type="number" name="lead_score" value="{{ old('lead_score',$company->lead_score ?? 0) }}" class="w-full rounded border-slate-300"></label>
<label>Expected Value <input type="number" step="0.01" name="expected_value" value="{{ old('expected_value',$company->expected_value) }}" class="w-full rounded border-slate-300"></label>
<label>Owner <select name="owner_id" class="w-full rounded border-slate-300"><option value="">-</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('owner_id',$company->owner_id)==$user->id)>{{ $user->name }}</option>@endforeach</select></label>
<label>Next Follow-up <input type="datetime-local" name="next_follow_up_at" class="w-full rounded border-slate-300"></label>
</div>
@if(! $company->exists)<h3 class="font-bold">Primary Contact</h3><div class="grid md:grid-cols-5 gap-4"><input name="contact_name" placeholder="Name" class="rounded border-slate-300"><input name="contact_position" placeholder="Position" class="rounded border-slate-300"><input name="contact_email" placeholder="Email" class="rounded border-slate-300"><input name="contact_phone" placeholder="Phone" class="rounded border-slate-300"><input name="contact_whatsapp" placeholder="WhatsApp" class="rounded border-slate-300"></div>@endif
<button class="px-5 py-2 bg-slate-900 text-white rounded-lg">Save</button>
</form></x-app-layout>
