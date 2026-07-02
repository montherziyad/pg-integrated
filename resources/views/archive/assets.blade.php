<x-app-layout>
    <x-slot name="header">Archived Assets</x-slot>
    <div class="space-y-6">
        <div class="flex justify-between"><div><h2 class="pg-title">Archived Assets</h2><p class="pg-subtitle mt-1">Files belonging to archived jobs.</p></div><a href="{{ route('archive.index') }}" class="pg-btn-secondary">← Archive</a></div>
        <div class="pg-card"><div class="pg-card-body overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b text-left text-slate-500"><th class="py-3">File</th><th>Job</th><th>Client</th><th>Stage</th><th>Version</th><th>Size</th><th></th></tr></thead>
            <tbody>@forelse($assets as $asset)<tr class="border-b last:border-b-0"><td class="py-4 font-semibold">{{ $asset->original_name ?? $asset->file_name }}</td><td>{{ $asset->job?->job_number ?? '-' }}</td><td>{{ $asset->job?->client?->name ?? '-' }}</td><td>{{ $asset->asset_stage }}</td><td>{{ $asset->version }}</td><td>{{ number_format($asset->file_size / 1024, 1) }} KB</td><td><a href="{{ route('assets.download', $asset) }}" class="pg-btn-secondary">Download</a></td></tr>@empty<tr><td colspan="7" class="py-10 text-center text-slate-500">No archived assets.</td></tr>@endforelse</tbody>
        </table><div class="mt-6">{{ $assets->links() }}</div></div></div>
    </div>
</x-app-layout>
