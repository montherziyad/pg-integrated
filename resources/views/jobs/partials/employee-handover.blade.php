<div class="pg-card border-l-4 border-l-amber-400">
    <div class="pg-card-body space-y-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
            <div>
                <h3 class="text-lg font-bold">Employee Handover to Traffic</h3>
                <p class="mt-1 text-sm text-slate-500">Send your finished design/source files or external link to Traffic. This does not publish anything to the client.</p>
            </div>
            @if($job->employee_handover_status === 'submitted_to_traffic')
                <span class="pg-badge pg-badge-review">Submitted to Traffic</span>
            @else
                <span class="pg-badge pg-badge-progress">Pending Handover</span>
            @endif
        </div>

        @if($job->employee_handover_submitted_at)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-950">
                Last handover by <strong>{{ $job->employeeHandoverSubmitter?->name ?? 'Employee' }}</strong>
                at {{ $job->employee_handover_submitted_at?->format('Y-m-d H:i') }}.
                @if($job->employee_handover_link)
                    <a href="{{ $job->employee_handover_link }}" target="_blank" class="ml-2 font-bold underline">Open link</a>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('jobs.handover', $job) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-2 font-semibold">Handover link</label>
                <input type="url" name="employee_handover_link" value="{{ old('employee_handover_link', $job->employee_handover_link) }}" placeholder="Dropbox, WeTransfer, Drive, or server link" class="w-full rounded-xl border-slate-300">
            </div>

            <div>
                <label class="block mb-2 font-semibold">Upload handover files</label>
                <input type="file" name="handover_files[]" multiple class="block w-full rounded-xl border border-slate-300 bg-white p-3">
                <p class="mt-1 text-xs text-slate-500">Allowed: design/source files, PDFs, images, videos, ZIP/RAR. Max 50MB each.</p>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Notes to Traffic</label>
                <textarea name="employee_handover_notes" rows="4" class="w-full rounded-xl border-slate-300" placeholder="Explain what was delivered and anything traffic/client service should review.">{{ old('employee_handover_notes', $job->employee_handover_notes) }}</textarea>
            </div>

            <button class="pg-btn-primary">Send Handover to Traffic</button>
        </form>
    </div>
</div>
