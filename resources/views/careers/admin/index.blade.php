<x-app-layout>
    <x-slot name="header">Careers</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Careers Management</h2>
                <p class="mt-1 text-sm text-slate-500">Manage website vacancies and review job applications.</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.career-applications.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Applications
                </a>
                <a href="{{ route('admin.careers.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                    + New Job
                </a>
            </div>
        </div>

        @if(! $moduleInstalled)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
                Careers tables are not installed yet. Run <code>php artisan migrate</code>.
            </div>
        @endif

        @foreach(['success', 'error'] as $flash)
            @if(session($flash))
                <div class="rounded-xl border {{ $flash === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700' }} px-4 py-3">
                    {{ session($flash) }}
                </div>
            @endif
        @endforeach

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="text-sm text-slate-500">Published / Total Jobs</div>
                <div class="mt-2 text-3xl font-bold">{{ method_exists($jobs, 'total') ? $jobs->total() : $jobs->count() }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="text-sm text-slate-500">New Applications</div>
                <div class="mt-2 text-3xl font-bold">{{ $newApplicationsCount }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="text-sm text-slate-500">Public Page</div>
                <a href="{{ route('careers.index') }}" target="_blank" class="mt-3 inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Open Join Us</a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="font-bold">Jobs</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Title</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Applications</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                                <tr class="border-t border-slate-100">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold">{{ $job->title }}</div>
                                        <div class="text-xs text-slate-500">{{ collect([$job->department, $job->location])->filter()->join(' • ') ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $job->employment_type }}</td>
                                    <td class="px-6 py-4">{{ $job->applications_count ?? $job->applications()->count() }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $job->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $job->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.careers.edit', $job) }}" class="text-sm font-semibold text-blue-700">Edit</a>
                                        <form action="{{ route('admin.careers.destroy', $job) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this job?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-sm font-semibold text-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">No jobs yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($jobs, 'links'))
                    <div class="border-t border-slate-100 px-6 py-4">{{ $jobs->links() }}</div>
                @endif
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-bold">Latest Applications</h3>
                    <a href="{{ route('admin.career-applications.index') }}" class="text-sm font-semibold text-blue-700">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse($applications as $application)
                        <a href="{{ route('admin.career-applications.show', $application) }}" class="block rounded-xl border border-slate-100 p-4 hover:bg-slate-50">
                            <div class="font-semibold">{{ $application->full_name }}</div>
                            <div class="text-xs text-slate-500">{{ $application->job?->title ?? 'General application' }}</div>
                            <div class="mt-2 text-xs uppercase text-slate-400">{{ $application->status }}</div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 p-4 text-sm text-slate-500">
                            No applications yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
