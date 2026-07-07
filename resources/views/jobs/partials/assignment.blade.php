<div class="pg-card">
    <div class="pg-card-body">
        <h3 class="text-lg font-bold mb-4">Assignment</h3>

        <form method="POST" action="{{ route('jobs.assign', $job->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label class="block mb-2 font-semibold">Team / Department</label>
                <select name="team_id" class="w-full rounded-xl border-slate-300" required>
                    <option value="">Select Team</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Department Manager / Supervisor</label>
                <select name="supervisor_id" class="w-full rounded-xl border-slate-300">
                    <option value="">Select Supervisor</option>
                    @foreach($users as $user)
                        @php($currentLeave = $user->employeeLeaves->first())
                        <option value="{{ $user->id }}" @disabled($currentLeave)>
                            {{ $user->name }}@if($currentLeave) — On Leave until {{ $currentLeave->ends_at?->format('Y-m-d') }} / returns {{ $currentLeave->returns_at?->format('Y-m-d') }}@endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Employee</label>
                <select name="user_id" class="w-full rounded-xl border-slate-300">
                    <option value="">Select Employee</option>
                    @foreach($users as $user)
                        @php($currentLeave = $user->employeeLeaves->first())
                        <option value="{{ $user->id }}" @disabled($currentLeave)>
                            {{ $user->name }}@if($currentLeave) — On Leave until {{ $currentLeave->ends_at?->format('Y-m-d') }} / returns {{ $currentLeave->returns_at?->format('Y-m-d') }}@endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Estimated Hours</label>
                <input type="number" step="0.5" min="0" name="estimated_hours" class="w-full rounded-xl border-slate-300">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-semibold">Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-300"></textarea>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="pg-btn-primary">
                    Assign Job
                </button>
            </div>
        </form>

        @include('jobs.partials.assignment-history')
    </div>
</div>