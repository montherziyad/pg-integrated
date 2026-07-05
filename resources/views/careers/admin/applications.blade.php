<x-app-layout>
    <x-slot name="header">Career Applications</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Career Applications</h2>
                <p class="mt-1 text-sm text-slate-500">Review and manage applicants from the Join Us page.</p>
            </div>
            <a href="{{ route('admin.careers.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold">Back to Careers</a>
        </div>

        @if(! $moduleInstalled)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
                Career application table is missing. Run <code>php artisan migrate</code>.
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Applicant</th>
                        <th class="px-6 py-3">Job</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Submitted</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $application->full_name }}</div>
                                <div class="text-xs text-slate-500">{{ $application->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $application->job?->title ?? 'General application' }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $application->status }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $application->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.career-applications.show', $application) }}" class="font-semibold text-blue-700">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No applications found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @if(method_exists($applications, 'links'))
                <div class="border-t border-slate-100 px-6 py-4">{{ $applications->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
