<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Activity Log</h3>

        <div class="space-y-4">
            @forelse($job->activities->sortByDesc('activity_at') as $activity)
                <div class="border-b last:border-b-0 pb-4">
                    <div class="flex items-center justify-between">
                        <div class="font-semibold">
                            {{ $activity->activity }}
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ $activity->activity_at ? \Carbon\Carbon::parse($activity->activity_at)->format('Y-m-d H:i') : '-' }}
                        </div>
                    </div>

                    <div class="text-sm text-slate-600 mt-1">
                        {{ $activity->description }}
                    </div>

                    <div class="text-xs text-slate-400 mt-1">
                        By: {{ $activity->user?->name ?? 'System' }}
                    </div>
                </div>
            @empty
                <div class="text-center text-slate-500 py-6">
                    No activity yet.
                </div>
            @endforelse
        </div>
    </div>
</div>