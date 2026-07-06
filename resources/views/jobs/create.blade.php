<x-app-layout>
    <x-slot name="header">
        Create New Job
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <form method="POST" action="{{ route('jobs.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="pg-card">
                <div class="pg-card-body">
                    <h2 class="pg-title">New Creative Job</h2>
                    <p class="pg-subtitle mt-1">Create a new studio task with brief and attachments.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div>
                            <label class="block mb-2 font-semibold">Job Title</label>
                            <input name="title" type="text" class="w-full rounded-xl border-slate-300" required>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Client</label>
                            <select name="client_id" class="w-full rounded-xl border-slate-300" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Project</label>
                            <select name="project_id" class="w-full rounded-xl border-slate-300">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Job Responsible / Final delivery owner</label>
                            <select name="responsible_user_id" class="w-full rounded-xl border-slate-300">
                                <option value="">Select responsible employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('responsible_user_id') === (string) $user->id)>{{ $user->name }} — {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Category</label>
                            <select name="job_category_id" class="w-full rounded-xl border-slate-300">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Priority</label>
                            <select name="priority" class="w-full rounded-xl border-slate-300">
                                <option value="LOW">Low</option>
                                <option value="MEDIUM" selected>Medium</option>
                                <option value="HIGH">High</option>
                                <option value="URGENT">Urgent</option>
                                <option value="CRITICAL">Critical</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">First Draft Due</label>
                            <input name="first_draft_due_at" type="datetime-local" class="w-full rounded-xl border-slate-300">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Final Delivery Due</label>
                            <input name="final_due_at" type="datetime-local" class="w-full rounded-xl border-slate-300">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Estimated Hours</label>
                            <input name="estimated_hours" type="number" step="0.5" min="0" class="w-full rounded-xl border-slate-300" value="0">
                        </div>
                    </div>

                    <div class="mt-8">
                        <label class="block mb-2 font-semibold">Brief</label>
                        <textarea name="brief" rows="8" class="w-full rounded-xl border-slate-300" placeholder="Paste the client brief here..."></textarea>
                    </div>

                    <div class="mt-8">
                        <label class="block mb-2 font-semibold">Brief Attachments</label>
                        <input type="file" name="attachments[]" multiple class="block w-full rounded-xl border border-slate-300 bg-white p-3">

                        <p class="mt-2 text-sm text-slate-500">
                            Allowed: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR, PSD, AI, AEP, PNG, JPG, MP4, MOV
                        </p>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <button type="submit" class="pg-btn-primary">Save Job</button>
                        <a href="{{ route('jobs.index') }}" class="pg-btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>