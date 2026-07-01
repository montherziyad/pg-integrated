<x-app-layout>
    <x-slot name="header">
        Administration
    </x-slot>

    <div class="space-y-6">

        <div>
            <h2 class="pg-title">Administration Center</h2>
            <p class="pg-subtitle mt-1">
                Manage users, teams, roles, branches, clients and projects.
            </p>
        </div>

        @include('admin.partials.nav')

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Users</div>
                    <div class="pg-stat-value">{{ $usersCount }}</div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Teams</div>
                    <div class="pg-stat-value">{{ $teamsCount }}</div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Roles</div>
                    <div class="pg-stat-value">{{ $rolesCount }}</div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Branches</div>
                    <div class="pg-stat-value">{{ $branchesCount }}</div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Clients</div>
                    <div class="pg-stat-value">{{ $clientsCount }}</div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="pg-stat-label">Projects</div>
                    <div class="pg-stat-value">{{ $projectsCount }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>