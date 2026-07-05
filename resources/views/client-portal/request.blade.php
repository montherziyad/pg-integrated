<x-dynamic-component component="client-portal.layout" :client="$client" title="New Request">
    <h1 class="text-4xl font-extrabold">Request a brief, campaign, or project</h1>
    <p class="mt-2 text-slate-500">Submit a new request with attachments, links, launch date, and required deliverables.</p>

    <form method="POST" action="{{ route('client.requests.store') }}" enctype="multipart/form-data" class="mt-8 rounded-3xl bg-white p-6">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Request type</span>
                <select name="type" class="w-full rounded-xl border-slate-300">
                    <option value="brief">Brief</option>
                    <option value="campaign">Campaign</option>
                    <option value="project">Project</option>
                    <option value="job">Job</option>
                    <option value="adaptation">Adaptation</option>
                </select>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Related project</span>
                <select name="project_id" class="w-full rounded-xl border-slate-300">
                    <option value="">New project / not related</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block md:col-span-2">
                <span class="mb-2 block text-sm font-semibold">Title</span>
                <input name="title" required class="w-full rounded-xl border-slate-300" placeholder="Ramadan campaign, new packaging adaptation, social calendar...">
            </label>
            <label class="block md:col-span-2">
                <span class="mb-2 block text-sm font-semibold">Brief</span>
                <textarea name="brief" rows="6" class="w-full rounded-xl border-slate-300" placeholder="Objective, audience, message, mandatories, references, timeline..."></textarea>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Target country</span>
                <input name="target_country" value="{{ $client->country }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Desired launch date</span>
                <input type="date" name="desired_launch_date" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Budget range</span>
                <input name="budget_range" class="w-full rounded-xl border-slate-300" placeholder="Optional">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Priority</span>
                <select name="priority" class="w-full rounded-xl border-slate-300">
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </label>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 p-4">
                <h2 class="font-bold">Deliverables</h2>
                @foreach (range(0, 5) as $index)
                    <input name="deliverables[]" class="mt-3 w-full rounded-xl border-slate-300" placeholder="KV, video, social posts, landing page...">
                @endforeach
            </div>
            <div class="rounded-2xl border border-slate-200 p-4">
                <h2 class="font-bold">External links</h2>
                @foreach (range(0, 4) as $index)
                    <input name="external_links[]" class="mt-3 w-full rounded-xl border-slate-300" placeholder="WeTransfer, Dropbox, Drive, reference link...">
                @endforeach
            </div>
        </div>

        <label class="mt-6 block">
            <span class="mb-2 block text-sm font-semibold">Attachments</span>
            <input type="file" name="attachments[]" multiple class="w-full rounded-xl border border-slate-300 bg-white p-3">
            <span class="mt-2 block text-xs text-slate-500">You can upload brief files, references, images, PDFs, or production assets.</span>
        </label>

        <button class="mt-6 rounded-xl bg-slate-950 px-6 py-3 font-bold text-white">Submit request</button>
    </form>
</x-dynamic-component>
