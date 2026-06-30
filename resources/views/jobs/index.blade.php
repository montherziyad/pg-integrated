<x-app-layout>
    <x-slot name="header">
        Jobs Management
    </x-slot>

    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="pg-title">Jobs Management</h2>
                <p class="pg-subtitle">Track briefs, assignments, reviews and delivery.</p>
            </div>

            <a href="{{ route('jobs.create') }}" class="pg-btn-primary">
                + New Job
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Total Jobs</div><div class="pg-stat-value">0</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">In Progress</div><div class="pg-stat-value">0</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Waiting Review</div><div class="pg-stat-value">0</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><div class="pg-stat-label">Urgent</div><div class="pg-stat-value">0</div></div></div>
        </div>

        <div class="pg-card">
            <div class="pg-card-body">
                <div class="flex gap-4 mb-6">
                    <input class="w-full rounded-xl border-slate-300" placeholder="Search jobs, clients, projects...">
                    <button class="pg-btn-secondary">Filter</button>
                </div>

                <div class="text-center py-16">
                    <h3 class="text-lg font-bold text-slate-900">No jobs yet</h3>
                    <p class="pg-subtitle mt-2">Create your first creative job to start tracking studio workflow.</p>

                    <a href="{{ route('jobs.create') }}" class="pg-btn-primary mt-6">
                        Create First Job
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>