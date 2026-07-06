<x-app-layout>
    <x-slot name="header">Email Intake</x-slot>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div><h2 class="pg-title">Outlook Intake Review</h2><p class="pg-subtitle mt-1">Validate, accept or reject incoming job emails before they enter production.</p></div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('email-intakes.sync-outlook') }}">
                    @csrf
                    <button type="submit" class="pg-btn-primary">Sync Outlook</button>
                </form>
                <a href="{{ route('admin.settings.index') }}" class="pg-btn-secondary">Outlook Settings</a>
            </div>
        </div>

        @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>@endif
        @if($errors->has('outlook'))<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">{{ $errors->first('outlook') }}</div>@endif

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach([['New',$counts['new']],['Valid',$counts['valid']],['Converted',$counts['converted']],['Rejected',$counts['rejected']],['Failed',$counts['failed']]] as [$label,$value])
                <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">{{ $label }}</div><div class="text-3xl font-bold mt-2">{{ $value }}</div></div></div>
            @endforeach
        </div>

        <div class="pg-card"><div class="pg-card-body overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Received</th><th>Sender</th><th>Subject</th><th>Job Number</th><th>Attachments</th><th>Validation</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($intakes as $intake)
                    <tr class="border-b last:border-b-0">
                        <td class="py-4">{{ $intake->received_at?->format('Y-m-d H:i') ?? '-' }}</td>
                        <td><strong class="block">{{ $intake->sender_name ?? '-' }}</strong><span class="text-xs text-slate-500">{{ $intake->sender_email }}</span></td>
                        <td class="max-w-sm">{{ str($intake->subject)->limit(70) }}</td>
                        <td>{{ $intake->extracted_job_number ?? '-' }}</td>
                        <td>{{ $intake->attachments_count }}</td>
                        <td><span class="pg-badge {{ $intake->validation_passed ? 'pg-badge-completed' : 'pg-badge-review' }}">{{ $intake->validation_passed ? 'Passed' : 'Failed' }}</span></td>
                        <td>{{ str($intake->status)->replace('_', ' ')->title() }}</td>
                        <td class="text-right"><a href="{{ route('email-intakes.show', $intake) }}" class="pg-btn-secondary">Review</a></td>
                    </tr>
                @empty<tr><td colspan="8" class="py-10 text-center text-slate-500">No Outlook emails received yet.</td></tr>@endforelse
                </tbody>
            </table>
            <div class="mt-6">{{ $intakes->links() }}</div>
        </div></div>
    </div>
</x-app-layout>
