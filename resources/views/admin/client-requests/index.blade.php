<x-app-layout>
    <x-slot name="header">Client Requests</x-slot>

    <div class="mb-6">
        <h2 class="text-2xl font-bold">Client brief, campaign, and project requests</h2>
        <p class="mt-1 text-sm text-slate-500">Requests submitted from the client portal, separated from traffic operations.</p>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr><th class="p-4">Request</th><th>Client</th><th>Service</th><th>Project</th><th>Type</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            @forelse ($requests as $request)
                <tr class="border-t">
                    <td class="p-4"><div class="font-bold">{{ $request->title }}</div><div class="text-xs text-slate-500">{{ $request->request_number }}</div></td>
                    <td>{{ $request->client?->name }}</td>
                    <td>{{ $request->service_name ?? '—' }}</td>
                    <td>{{ $request->project?->name ?? 'New project' }}</td>
                    <td>{{ $request->type }}</td>
                    <td><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold">{{ $request->status }}</span></td>
                    <td><a href="{{ route('admin.client-requests.show', $request) }}" class="font-bold text-blue-600">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="p-8 text-center text-slate-500">No client requests yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
</x-app-layout>
