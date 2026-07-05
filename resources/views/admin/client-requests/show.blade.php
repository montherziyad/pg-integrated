<x-app-layout>
    <x-slot name="header">Client Request</x-slot>

    <div class="grid gap-6 xl:grid-cols-[1fr_.45fr]">
        <section class="rounded-2xl bg-white p-6 shadow">
            <div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $request->request_number }} · {{ $request->type }}</div>
            <h2 class="mt-2 text-3xl font-bold">{{ $request->title }}</h2>
            <p class="mt-4 whitespace-pre-line leading-7 text-slate-600">{{ $request->brief ?: 'No brief text.' }}</p>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs text-slate-500">Country</div><div class="font-bold">{{ $request->target_country ?: '—' }}</div></div>
                <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs text-slate-500">Launch</div><div class="font-bold">{{ $request->desired_launch_date?->format('Y-m-d') ?? '—' }}</div></div>
                <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs text-slate-500">Priority</div><div class="font-bold">{{ $request->priority }}</div></div>
            </div>

            <h3 class="mt-8 text-xl font-bold">Deliverables</h3>
            <div class="mt-3 flex flex-wrap gap-2">
                @forelse ($request->deliverables ?? [] as $deliverable)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold">{{ $deliverable }}</span>
                @empty
                    <span class="text-slate-500">No deliverables listed.</span>
                @endforelse
            </div>

            <h3 class="mt-8 text-xl font-bold">Attachments</h3>
            <div class="mt-3 space-y-2">
                @forelse ($request->attachments ?? [] as $attachment)
                    <a href="{{ $attachment['url'] }}" target="_blank" class="block rounded-xl border border-slate-200 p-3 font-semibold">{{ $attachment['name'] }}</a>
                @empty
                    <div class="text-slate-500">No attachments.</div>
                @endforelse
            </div>

            <h3 class="mt-8 text-xl font-bold">External links</h3>
            <div class="mt-3 space-y-2">
                @forelse ($request->external_links ?? [] as $link)
                    <a href="{{ $link }}" target="_blank" class="block rounded-xl border border-slate-200 p-3 font-semibold text-blue-600">{{ $link }}</a>
                @empty
                    <div class="text-slate-500">No links.</div>
                @endforelse
            </div>
        </section>

        <aside class="rounded-2xl bg-white p-6 shadow">
            <h3 class="text-xl font-bold">Client</h3>
            <div class="mt-4 space-y-3 text-sm">
                <div><span class="text-slate-500">Name:</span> <strong>{{ $request->client?->name }}</strong></div>
                <div><span class="text-slate-500">Email:</span> <strong>{{ $request->client?->email }}</strong></div>
                <div><span class="text-slate-500">Company:</span> <strong>{{ $request->client?->company_name ?: '—' }}</strong></div>
                <div><span class="text-slate-500">Project:</span> <strong>{{ $request->project?->name ?: 'New project' }}</strong></div>
                <div><span class="text-slate-500">Status:</span> <strong>{{ $request->status }}</strong></div>
            </div>
        </aside>
    </div>
</x-app-layout>
