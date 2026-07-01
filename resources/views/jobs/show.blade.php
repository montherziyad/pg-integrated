<x-app-layout>
    <x-slot name="header">
        Job Details
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">{{ $job->job_number }}</h2>
                <p class="pg-subtitle">{{ $job->title }}</p>
            </div>

            <a href="{{ route('jobs.index') }}" class="pg-btn-secondary">
                Back to Jobs
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">

                <div class="pg-card">
                    <div class="pg-card-body">
                        <h3 class="text-lg font-bold mb-4">Brief</h3>
                        <p class="text-slate-700 whitespace-pre-line">
                            {{ $job->brief ?? 'No brief provided.' }}
                        </p>
                    </div>
                </div>

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
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">Employee</label>
                                <select name="user_id" class="w-full rounded-xl border-slate-300">
                                    <option value="">Select Employee</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                    </div>
                </div>

                <div class="pg-card">
                    <div class="pg-card-body">
                        <h3 class="text-lg font-bold mb-4">Timeline</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="pg-stat-label">Received</div>
                                <div class="font-semibold">
                                    {{ $job->received_at ? \Carbon\Carbon::parse($job->received_at)->format('Y-m-d H:i') : '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="pg-stat-label">First Draft Due</div>
                                <div class="font-semibold">
                                    {{ $job->first_draft_due_at ? \Carbon\Carbon::parse($job->first_draft_due_at)->format('Y-m-d H:i') : '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="pg-stat-label">Final Due</div>
                                <div class="font-semibold">
                                    {{ $job->final_due_at ? \Carbon\Carbon::parse($job->final_due_at)->format('Y-m-d H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pg-card">
                <div class="pg-card-body space-y-4">
                    <div>
                        <div class="pg-stat-label">Client</div>
                        <div class="font-semibold">{{ $job->client?->name ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="pg-stat-label">Project</div>
                        <div class="font-semibold">{{ $job->project?->name ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="pg-stat-label">Category</div>
                        <div class="font-semibold">{{ $job->category?->name ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="pg-stat-label">Priority</div>
                        <div class="font-semibold">{{ $job->priority }}</div>
                    </div>

                    <div>
                        <div class="pg-stat-label">Workflow Stage</div>
                        <div class="font-semibold">
                            {{ $job->currentWorkflowStage?->name ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="pg-stat-label">Estimated Hours</div>
                        <div class="font-semibold">
                            {{ $job->estimated_hours }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>