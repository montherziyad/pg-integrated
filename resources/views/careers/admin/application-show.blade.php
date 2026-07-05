<x-app-layout>
    <x-slot name="header">Application Details</x-slot>

    <div class="mx-auto max-w-5xl space-y-6">
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $application->full_name }}</h2>
                        <p class="mt-1 text-slate-500">{{ $application->current_title ?: 'Applicant' }}</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase text-slate-700">{{ $application->status }}</span>
                </div>

                <dl class="mt-6 grid gap-4 md:grid-cols-2">
                    <div><dt class="text-xs uppercase text-slate-400">Email</dt><dd class="font-semibold">{{ $application->email }}</dd></div>
                    <div><dt class="text-xs uppercase text-slate-400">Phone</dt><dd class="font-semibold">{{ $application->phone ?: '-' }}</dd></div>
                    <div><dt class="text-xs uppercase text-slate-400">Job</dt><dd class="font-semibold">{{ $application->job?->title ?? 'General application' }}</dd></div>
                    <div><dt class="text-xs uppercase text-slate-400">Submitted</dt><dd class="font-semibold">{{ $application->created_at?->format('Y-m-d H:i') }}</dd></div>
                    <div><dt class="text-xs uppercase text-slate-400">Portfolio</dt><dd class="font-semibold">@if($application->portfolio_url)<a class="text-blue-700" href="{{ $application->portfolio_url }}" target="_blank">Open link</a>@else - @endif</dd></div>
                    <div><dt class="text-xs uppercase text-slate-400">LinkedIn</dt><dd class="font-semibold">@if($application->linkedin_url)<a class="text-blue-700" href="{{ $application->linkedin_url }}" target="_blank">Open link</a>@else - @endif</dd></div>
                </dl>

                <div class="mt-8">
                    <h3 class="mb-2 font-bold">Message</h3>
                    <div class="rounded-xl bg-slate-50 p-4 leading-7 text-slate-700 whitespace-pre-line">{{ $application->message ?: 'No message provided.' }}</div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h3 class="font-bold">Review Status</h3>
                    <form method="POST" action="{{ route('admin.career-applications.update', $application) }}" class="mt-4 space-y-4">
                        @csrf
                        @method('PATCH')

                        <select name="status" class="w-full rounded-xl border-slate-300">
                            @foreach(['new' => 'New', 'reviewing' => 'Reviewing', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected', 'hired' => 'Hired'] as $value => $label)
                                <option value="{{ $value }}" @selected($application->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <button class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Update status</button>
                    </form>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h3 class="font-bold">Files</h3>
                    @if($application->cv_path)
                        <a href="{{ route('admin.career-applications.cv', $application) }}" class="mt-4 inline-flex w-full justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold">
                            Download CV
                        </a>
                    @else
                        <p class="mt-3 text-sm text-slate-500">No CV uploaded.</p>
                    @endif
                </div>

                <a href="{{ route('admin.career-applications.index') }}" class="block rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold">
                    Back to applications
                </a>
            </aside>
        </div>
    </div>
</x-app-layout>
