<x-app-layout><x-slot name="header">Marketing Automation</x-slot>
<div class="space-y-6">
@if(session('status'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('status') }}</div>@endif
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between"><div><h2 class="pg-title">Marketing Dashboard</h2><p class="pg-subtitle mt-1">PG Integrated outbound marketing through approval-controlled Outlook campaigns.</p></div><a href="{{ route('marketing.create') }}" class="pg-btn-primary">+ New Outlook Campaign</a></div>
<div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
@foreach([['Campaigns',$stats['total']],['Draft',$stats['draft']],['Pending Approval',$stats['pending']],['Approved / Active',$stats['approved']],['Completed',$stats['completed']],['Recipients',$stats['recipients']],['Sent',$stats['sent']],['Response Rate',$stats['response_rate'].'%']] as [$label,$value])
<div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">{{ $label }}</div><div class="mt-2 text-3xl font-bold">{{ $value }}</div></div></div>
@endforeach
</div>
<div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 text-blue-900"><strong>Safe Outlook mode:</strong> Campaign creation, audience selection, and approvals are active. Actual sending is disabled until Microsoft Graph Mail.Send permission and sending limits are configured.</div>
<div class="pg-card"><div class="pg-card-body overflow-x-auto"><table class="w-full min-w-[900px] text-sm"><thead><tr class="border-b text-left text-slate-500"><th class="py-3">Campaign</th><th>Channel</th><th>Status</th><th>Recipients</th><th>Schedule</th><th>Created by</th><th></th></tr></thead><tbody>
@forelse($campaigns as $campaign)<tr class="border-b last:border-0"><td class="py-4"><div class="font-bold">{{ $campaign->name }}</div><div class="text-xs text-slate-500">{{ $campaign->email_subject ?? '-' }}</div></td><td>Outlook Email</td><td><span class="pg-badge">{{ str($campaign->status)->replace('_',' ')->title() }}</span></td><td>{{ $campaign->recipients_count }}</td><td>{{ $campaign->scheduled_at?->format('Y-m-d H:i') ?? '-' }}</td><td>{{ $campaign->creator?->name ?? '-' }}</td><td class="text-right"><a href="{{ route('marketing.show',$campaign) }}" class="pg-btn-secondary">Review</a></td></tr>
@empty<tr><td class="py-10 text-center text-slate-500" colspan="7">No campaigns yet.</td></tr>@endforelse
</tbody></table></div></div>
{{ $campaigns->links() }}
</div></x-app-layout>
