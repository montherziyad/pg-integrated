<x-app-layout>

    <x-slot name="header">
        Create New Job
    </x-slot>

    <div class="max-w-7xl mx-auto">

        <div class="pg-card">

            <div class="pg-card-body">

                <div class="mb-8">

                    <h2 class="pg-title">
                        New Creative Job
                    </h2>

                    <p class="pg-subtitle">
                        Create a new task for the studio.
                    </p>

                </div>

                <form>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block mb-2 font-semibold">
                                Job Title
                            </label>

                            <input
                                type="text"
                                class="w-full rounded-xl border-slate-300"
                                placeholder="Ramadan Campaign Key Visual">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Client
                            </label>

                            <select class="w-full rounded-xl border-slate-300">
                                <option>Select Client</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Project
                            </label>

                            <select class="w-full rounded-xl border-slate-300">
                                <option>Select Project</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Category
                            </label>

                            <select class="w-full rounded-xl border-slate-300">
                                <option>Media Campaign</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Priority
                            </label>

                            <select class="w-full rounded-xl border-slate-300">
                                <option>Medium</option>
                                <option>High</option>
                                <option>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                First Draft Date
                            </label>

                            <input
                                type="date"
                                class="w-full rounded-xl border-slate-300">
                        </div>

                    </div>

                    <div class="mt-8">

                        <label class="block mb-2 font-semibold">
                            Brief
                        </label>

                        <textarea
                            rows="8"
                            class="w-full rounded-xl border-slate-300"
                            placeholder="Paste the client brief here..."></textarea>

                    </div>
<div class="mt-8">

    <label class="block mb-2 font-semibold">
        Brief Attachments
    </label>

    <input
        type="file"
        name="attachments[]"
        multiple
        class="block w-full rounded-xl border border-slate-300 bg-white p-3">

    <p class="mt-2 text-sm text-slate-500">
        Allowed:
        PDF, DOC, DOCX, PPT, PPTX,
        XLS, XLSX,
        ZIP, RAR,
        PSD, AI, INDD, AEP,
        PNG, JPG, JPEG, SVG,
        MP4, MOV
    </p>

</div>
                    <div class="mt-8 flex gap-4">

                        <button
                            class="pg-btn-primary">

                            Save Job

                        </button>

                        <a
                            href="{{ route('jobs.index') }}"
                            class="pg-btn-secondary">

                            Cancel

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>