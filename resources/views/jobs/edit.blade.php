<x-app-layout>
    <x-slot name="header">Edit Job</x-slot>

    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('jobs.update', $job) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="flex justify-between items-center">
                        <div><h2 class="pg-title">Edit Creative Job</h2><p class="pg-subtitle mt-1">{{ $job->job_number }}</p></div>
                        <a href="{{ route('jobs.show', $job) }}" class="pg-btn-secondary">← Back</a>
                    </div>

                    @if($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                            <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div><label class="block mb-2 font-semibold">Job Title</label><input name="title" value="{{ old('title', $job->title) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div><label class="block mb-2 font-semibold">Client</label><select name="client_id" class="w-full rounded-xl border-slate-300" required>@foreach($clients as $client)<option value="{{ $client->id }}" @selected((string) old('client_id', $job->client_id) === (string) $client->id)>{{ $client->name }}</option>@endforeach</select></div>
                        <div><label class="block mb-2 font-semibold">Project</label><select name="project_id" class="w-full rounded-xl border-slate-300"><option value="">Select Project</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((string) old('project_id', $job->project_id) === (string) $project->id)>{{ $project->name }}</option>@endforeach</select></div>
                        <div><label class="block mb-2 font-semibold">Category</label><select name="job_category_id" class="w-full rounded-xl border-slate-300"><option value="">Select Category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) old('job_category_id', $job->job_category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                        <div><label class="block mb-2 font-semibold">Priority</label><select name="priority" class="w-full rounded-xl border-slate-300">@foreach(['LOW','MEDIUM','HIGH','URGENT','CRITICAL'] as $priority)<option value="{{ $priority }}" @selected(old('priority', $job->priority) === $priority)>{{ str($priority)->title() }}</option>@endforeach</select></div>
                        <div><label class="block mb-2 font-semibold">First Draft Due</label><input name="first_draft_due_at" type="datetime-local" value="{{ old('first_draft_due_at', $job->first_draft_due_at?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-slate-300"></div>
                        <div><label class="block mb-2 font-semibold">Final Delivery Due</label><input name="final_due_at" type="datetime-local" value="{{ old('final_due_at', $job->final_due_at?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-slate-300"></div>
                        <div><label class="block mb-2 font-semibold">Estimated Hours</label><input name="estimated_hours" type="number" step="0.5" min="0" value="{{ old('estimated_hours', $job->estimated_hours) }}" class="w-full rounded-xl border-slate-300"></div>
                    </div>

                    <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-slate-950">Client Portal Visibility</h3>
                            <p class="mt-1 text-sm text-slate-600">هذه الحقول هي التي تظهر للعميل في Dashboard و Projects مثل المرحلة، النسبة، الروابط، وتاريخ التسليم.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 font-semibold">Stage shown to client</label>
                                <select name="current_workflow_stage_id" class="w-full rounded-xl border-slate-300">
                                    <option value="">Preparing / Not selected</option>
                                    @foreach($workflowStages as $stage)
                                        <option value="{{ $stage->id }}" @selected((string) old('current_workflow_stage_id', $job->current_workflow_stage_id) === (string) $stage->id)>{{ $stage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Progress %</label>
                                <input name="completion_percentage" type="number" min="0" max="100" value="{{ old('completion_percentage', $job->completion_percentage) }}" class="w-full rounded-xl border-slate-300">
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Brief / Dropbox link</label>
                                <input name="dropbox_folder_path" value="{{ old('dropbox_folder_path', $job->dropbox_folder_path) }}" placeholder="https://www.dropbox.com/..." class="w-full rounded-xl border-slate-300">
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Final / WeTransfer link</label>
                                <input name="final_delivery_path" value="{{ old('final_delivery_path', $job->final_delivery_path) }}" placeholder="https://wetransfer.com/..." class="w-full rounded-xl border-slate-300">
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block mb-2 font-semibold">Client notes</label>
                            <textarea name="client_notes" rows="4" class="w-full rounded-xl border-slate-300" placeholder="Short note visible internally and ready for client-facing updates.">{{ old('client_notes', $job->client_notes) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8"><label class="block mb-2 font-semibold">Brief</label><textarea name="brief" rows="8" class="w-full rounded-xl border-slate-300">{{ old('brief', $job->brief) }}</textarea></div>
                    <div class="mt-8 flex gap-4"><button class="pg-btn-primary">Update Job</button><a href="{{ route('jobs.show', $job) }}" class="pg-btn-secondary">Cancel</a></div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
