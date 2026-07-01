<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Brief Attachments</h3>

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

        <div class="mt-8">
            <h4 class="font-bold mb-4">Uploaded Files</h4>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-slate-500">
                        <th class="py-3">File</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Uploaded By</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($job->assets as $asset)
                        <tr class="border-b last:border-b-0">
                            <td class="py-3">{{ $asset->original_name }}</td>
                            <td>{{ strtoupper($asset->file_type) }}</td>
                            <td>{{ number_format($asset->file_size / 1024, 2) }} KB</td>
                            <td>{{ $asset->uploader?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">
                                No attachments uploaded.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>