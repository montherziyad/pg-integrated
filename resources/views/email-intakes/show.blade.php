<x-app-layout>
    <x-slot name="header">Review Email Intake</x-slot>
    <div class="space-y-6">
        <div class="flex justify-between gap-4"><div><h2 class="pg-title">{{ $intake->subject ?? 'Untitled Email' }}</h2><p class="pg-subtitle mt-1">{{ $intake->sender_name }} · {{ $intake->sender_email }}</p></div><a href="{{ route('email-intakes.index') }}" class="pg-btn-secondary">← Inbox</a></div>

        @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700"><ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Email</h3><div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm"><div><span class="pg-stat-label">Received</span><div>{{ $intake->received_at?->format('Y-m-d H:i') ?? '-' }}</div></div><div><span class="pg-stat-label">Job Number</span><div>{{ $intake->extracted_job_number ?? '-' }}</div></div><div class="md:col-span-2"><span class="pg-stat-label">To</span><div>{{ collect($intake->to_recipients)->pluck('email')->join(', ') ?: '-' }}</div></div><div class="md:col-span-2"><span class="pg-stat-label">CC</span><div>{{ collect($intake->cc_recipients)->pluck('email')->join(', ') ?: '-' }}</div></div></div><div class="mt-6 whitespace-pre-wrap text-sm leading-6">{{ strip_tags((string) $intake->body) }}</div></div></div>

                <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Brief Attachments</h3><div class="space-y-3">@forelse($intake->attachments as $attachment)<div class="flex justify-between rounded-xl border border-slate-200 p-3"><span><strong>{{ $attachment->name }}</strong><span class="block text-xs text-slate-500">{{ $attachment->content_type }} · {{ number_format($attachment->size / 1024, 1) }} KB</span></span><span class="pg-badge {{ $attachment->is_brief ? 'pg-badge-completed' : 'pg-badge-review' }}">{{ $attachment->is_brief ? 'Brief' : 'Ignored' }}</span></div>@empty<div class="text-slate-500">No attachments.</div>@endforelse</div></div></div>
            </div>

            <div class="space-y-6">
                <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Validation Rules</h3><div class="space-y-3">@foreach($intake->validations as $validation)<div class="rounded-xl p-3 {{ $validation->passed ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-800' }}"><strong class="block">{{ str($validation->rule)->replace('_', ' ')->title() }}</strong><span class="text-sm">{{ $validation->message }}</span></div>@endforeach</div></div></div>

                @if($intake->status === 'NEW' && $intake->validation_passed)
                    <div class="pg-card"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Accept and Create Job</h3><form method="POST" action="{{ route('email-intakes.accept', $intake) }}" class="space-y-4">@csrf
                        <div><label class="block mb-1 font-semibold">Client</label><select name="client_id" class="w-full rounded-xl border-slate-300" required><option value="">Select client</option>@foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
                        <div><label class="block mb-1 font-semibold">Project</label><select name="project_id" class="w-full rounded-xl border-slate-300"><option value="">Select project</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->name }}</option>@endforeach</select></div>
                        <div><label class="block mb-1 font-semibold">Category</label><select name="job_category_id" class="w-full rounded-xl border-slate-300"><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
                        <div><label class="block mb-1 font-semibold">Priority</label><select name="priority" class="w-full rounded-xl border-slate-300">@foreach(['LOW','MEDIUM','HIGH','URGENT','CRITICAL'] as $priority)<option value="{{ $priority }}" @selected($priority === 'MEDIUM')>{{ $priority }}</option>@endforeach</select></div>
                        <div><label class="block mb-1 font-semibold">Final Due</label><input type="datetime-local" name="final_due_at" class="w-full rounded-xl border-slate-300"></div>
                        <button class="pg-btn-primary w-full justify-center">Accept Email</button>
                    </form></div></div>
                @endif

                @if($intake->status === 'NEW')
                    <div class="pg-card border-red-200"><div class="pg-card-body"><h3 class="text-lg font-bold mb-4">Reject Email</h3><form method="POST" action="{{ route('email-intakes.reject', $intake) }}" class="space-y-4">@csrf<textarea name="rejection_reason" rows="4" class="w-full rounded-xl border-slate-300" placeholder="Rejection reason is required" required minlength="5"></textarea><button class="inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Reject Email</button></form></div></div>
                @elseif($intake->rejection_reason)
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800"><strong class="block">Rejection Reason</strong>{{ $intake->rejection_reason }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
