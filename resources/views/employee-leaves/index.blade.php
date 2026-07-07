<x-app-layout>
    <x-slot name="header">Employee Leaves</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Employee Leaves</h2>
                <p class="pg-subtitle mt-1">Submit leave requests with attachments and keep workload assignment aware of employee availability.</p>
            </div>
            @if($canReview)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-bold text-emerald-800">Reviewer access: HR / General Manager / Super Admin</div>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
        @endif

        <section class="pg-card">
            <div class="pg-card-body">
                <h3 class="mb-4 text-lg font-bold">New leave request</h3>
                <form method="POST" action="{{ route('employee-leaves.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-3">
                    @csrf

                    @if($canReview)
                        <div>
                            <label class="mb-2 block font-semibold">Employee</label>
                            <select name="user_id" class="w-full rounded-xl border-slate-300" required>
                                <option value="">Select employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} · {{ $user->team?->name ?? 'No team' }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <label class="mb-2 block font-semibold">Leave type</label>
                        <select name="leave_type" class="w-full rounded-xl border-slate-300" required>
                            @foreach($leaveTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('leave_type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('leave_type')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold">From</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="w-full rounded-xl border-slate-300" required>
                        <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold">To</label>
                        <input type="date" name="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-xl border-slate-300" required>
                        <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold">Return to work</label>
                        <input type="date" name="returns_at" value="{{ old('returns_at') }}" class="w-full rounded-xl border-slate-300" required>
                        <x-input-error :messages="$errors->get('returns_at')" class="mt-2" />
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold">Attachment</label>
                        <input type="file" name="attachment" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <p class="mt-1 text-xs text-slate-500">PDF, image, Word. Max 10MB.</p>
                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-2 block font-semibold">Reason</label>
                        <textarea name="reason" rows="3" class="w-full rounded-xl border-slate-300">{{ old('reason') }}</textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>

                    <div class="md:col-span-3">
                        <button class="pg-btn-primary">Submit leave request</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="pg-card">
            <div class="pg-card-body">
                <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <h3 class="text-lg font-bold">Leave requests</h3>
                    <form method="GET" action="{{ route('employee-leaves.index') }}" class="flex flex-col gap-2 md:flex-row">
                        <input name="q" value="{{ request('q') }}" placeholder="Search employee, status, type..." class="rounded-xl border-slate-300 px-4 py-2 text-sm">
                        <select name="status" class="rounded-xl border-slate-300 px-4 py-2 text-sm">
                            <option value="">All status</option>
                            @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-bold text-white">Search</button>
                        @if(request('q') || request('status'))<a href="{{ route('employee-leaves.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold text-slate-600">Clear</a>@endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left text-slate-500"><th class="py-3">Employee</th><th>Leave</th><th>Dates</th><th>Return</th><th>Attachment</th><th>Status</th><th>Review</th><th></th></tr></thead>
                        <tbody>
                        @forelse($leaves as $leave)
                            <tr class="border-b last:border-b-0 align-top">
                                <td class="py-4 font-semibold"><div>{{ $leave->employee?->name }}</div><div class="text-xs text-slate-500">{{ $leave->employee?->team?->name ?? '-' }}</div></td>
                                <td><div class="font-semibold">{{ $leaveTypes[$leave->leave_type] ?? str($leave->leave_type)->title() }}</div><div class="max-w-xs text-xs text-slate-500">{{ $leave->reason ?: '-' }}</div></td>
                                <td>{{ $leave->starts_at?->format('Y-m-d') }} → {{ $leave->ends_at?->format('Y-m-d') }}</td>
                                <td>{{ $leave->returns_at?->format('Y-m-d') }}</td>
                                <td>@if($leave->attachment_path)<a class="text-blue-700 underline" href="{{ route('employee-leaves.download', $leave) }}">{{ $leave->attachment_original_name ?: 'Download' }}</a>@else - @endif</td>
                                <td><span class="pg-badge {{ $leave->status === 'approved' ? 'pg-badge-completed' : ($leave->status === 'pending' ? 'pg-badge-progress' : 'pg-badge-review') }}">{{ str($leave->status)->title() }}</span></td>
                                <td><div class="text-xs text-slate-500">{{ $leave->reviewer?->name ?? '-' }}</div><div class="max-w-xs text-xs text-slate-500">{{ $leave->review_note }}</div></td>
                                <td class="min-w-64">
                                    @if($canReview && $leave->status === 'pending')
                                        <div class="space-y-2">
                                            <form method="POST" action="{{ route('employee-leaves.approve', $leave) }}" class="flex gap-2">@csrf @method('PATCH')<input name="review_note" placeholder="Approval note" class="w-full rounded-xl border-slate-300 px-3 py-2 text-xs"><button class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white">Approve</button></form>
                                            <form method="POST" action="{{ route('employee-leaves.reject', $leave) }}" class="flex gap-2">@csrf @method('PATCH')<input name="review_note" placeholder="Rejection note" class="w-full rounded-xl border-slate-300 px-3 py-2 text-xs"><button class="rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white">Reject</button></form>
                                        </div>
                                    @elseif($leave->status === 'pending' && ($leave->user_id === auth()->id() || $canReview))
                                        <form method="POST" action="{{ route('employee-leaves.cancel', $leave) }}">@csrf @method('PATCH')<button class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600">Cancel</button></form>
                                    @else
                                        <span class="text-xs text-slate-400">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-10 text-center text-slate-500">No leave requests found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $leaves->links() }}</div>
            </div>
        </section>
    </div>
</x-app-layout>
