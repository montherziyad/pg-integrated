<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Brief Attachments</h3>

        @if(Auth::user()?->canAccessScreen('traffic_board') || Auth::user()?->canAccessScreen('email_intake') || Auth::user()?->canAccessScreen('team_workload'))
            <form method="POST"
                  action="{{ route('jobs.attachments.upload', $job->id) }}"
                  enctype="multipart/form-data"
                  class="space-y-4">
                @csrf

                <input type="file"
                       name="attachments[]"
                       multiple
                       class="block w-full rounded-xl border border-slate-300 bg-white p-3">

                <button type="submit" class="pg-btn-primary">
                    Upload Attachments
                </button>
            </form>
        @else
            <p class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">Brief attachments are read-only for this role. Use Employee Handover to send finished work to Traffic.</p>
        @endif

        <div class="mt-8">
            <h4 class="font-bold mb-4">Uploaded Files</h4>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-slate-500">
                        <th class="py-3">File</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Uploaded By</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($job->assets as $asset)
                        <tr class="border-b last:border-b-0">
                            <td class="py-3">{{ $asset->original_name }}</td>
                            <td>{{ strtoupper($asset->file_type) }}</td>
                            <td>{{ number_format($asset->file_size / 1024, 2) }} KB</td>
                            <td>{{ $asset->uploader?->name ?? '-' }}</td>
                            <td class="text-right">
                                <a href="{{ route('assets.download', $asset->id) }}"
                                   class="pg-btn-secondary">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">
                                No attachments uploaded.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>