<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Timeline</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="pg-stat-label">Received</div>
                <div class="font-semibold">
                    {{ $job->received_at ? \Carbon\Carbon::parse($job->received_at)->format('Y-m-d H:i') : '-' }}
                </div>
            </div>

            <div>
                <div class="pg-stat-label">First Draft Due</div>
                <div class="font-semibold">
                    {{ $job->first_draft_due_at ? \Carbon\Carbon::parse($job->first_draft_due_at)->format('Y-m-d H:i') : '-' }}
                </div>
            </div>

            <div>
                <div class="pg-stat-label">Final Due</div>
                <div class="font-semibold">
                    {{ $job->final_due_at ? \Carbon\Carbon::parse($job->final_due_at)->format('Y-m-d H:i') : '-' }}
                </div>
            </div>
        </div>
    </div>
</div>