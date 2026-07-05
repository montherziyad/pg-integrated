<x-app-layout>
    <x-slot name="header">{{ $job->exists ? 'Edit Career Job' : 'New Career Job' }}</x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <h2 class="text-2xl font-bold">{{ $job->exists ? 'Edit Career Job' : 'Create Career Job' }}</h2>
            <p class="mt-1 text-sm text-slate-500">This job will appear on the public Join Us page when published.</p>
        </div>

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ $job->exists ? route('admin.careers.update', $job) : route('admin.careers.store') }}" class="rounded-2xl bg-white p-6 shadow-sm">
            @csrf
            @if($job->exists)
                @method('PUT')
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold">Job title</label>
                    <input name="title" value="{{ old('title', $job->title) }}" class="w-full rounded-xl border-slate-300" required>
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Department</label>
                    <input name="department" value="{{ old('department', $job->department) }}" class="w-full rounded-xl border-slate-300">
                    @error('department') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Location</label>
                    <input name="location" value="{{ old('location', $job->location) }}" class="w-full rounded-xl border-slate-300">
                    @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Employment type</label>
                    <input name="employment_type" value="{{ old('employment_type', $job->employment_type ?? 'Full-time') }}" class="w-full rounded-xl border-slate-300" required>
                    @error('employment_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Published at</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($job->published_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-300">
                    @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Closes at</label>
                    <input type="datetime-local" name="closes_at" value="{{ old('closes_at', optional($job->closes_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-300">
                    @error('closes_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-7">
                    <input type="hidden" name="is_published" value="0">
                    <input id="is_published" type="checkbox" name="is_published" value="1" @checked(old('is_published', $job->is_published ?? true)) class="rounded border-slate-300">
                    <label for="is_published" class="text-sm font-semibold">Publish on website</label>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold">Summary</label>
                    <textarea name="summary" rows="3" class="w-full rounded-xl border-slate-300">{{ old('summary', $job->summary) }}</textarea>
                    @error('summary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold">Description</label>
                    <textarea name="description" rows="5" class="w-full rounded-xl border-slate-300">{{ old('description', $job->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Responsibilities</label>
                    <textarea name="responsibilities_text" rows="8" class="w-full rounded-xl border-slate-300" placeholder="One item per line">{{ old('responsibilities_text', collect($job->responsibilities ?? [])->join("\n")) }}</textarea>
                    @error('responsibilities_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Requirements</label>
                    <textarea name="requirements_text" rows="8" class="w-full rounded-xl border-slate-300" placeholder="One item per line">{{ old('requirements_text', collect($job->requirements ?? [])->join("\n")) }}</textarea>
                    @error('requirements_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.careers.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold">Cancel</a>
                <button class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white">
                    {{ $job->exists ? 'Save changes' : 'Create job' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
