<div class="pg-card">
    <div class="pg-card-body">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <a href="{{ route('admin.users.index') }}" class="pg-btn-secondary justify-center">
                Users
            </a>

            <a href="{{ route('admin.teams.index') }}" class="pg-btn-secondary justify-center">
                Teams
            </a>

            <a href="{{ route('admin.roles.index') }}" class="pg-btn-secondary justify-center">
                Roles
            </a>

            <a href="{{ route('admin.branches.index') }}" class="pg-btn-secondary justify-center">
                Branches
            </a>

            <a href="{{ route('admin.clients.index') }}" class="pg-btn-secondary justify-center">
                Clients
            </a>

            <a href="{{ route('admin.projects.index') }}" class="pg-btn-secondary justify-center">
                Projects
            </a>

            <a href="{{ route('admin.cms.index') }}" class="pg-btn-secondary justify-center">
                Website Pages
            </a>

            <a href="{{ route('admin.categories.index') }}" class="pg-btn-secondary justify-center">
                Categories
            </a>

            <a href="{{ route('admin.settings.index') }}" class="pg-btn-secondary justify-center">
                Settings
            </a>

            <a href="{{ route('admin.settings.outlook') }}" class="pg-btn-secondary justify-center">
                Outlook Intake
            </a>

            @if(Auth::user()?->role?->code === 'SUPER_ADMIN')
                <a href="{{ route('admin.settings.reset-test-data.index') }}" class="justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-center font-semibold text-red-700 hover:bg-red-100">
                    Test Data Reset
                </a>
            @endif

        </div>
    </div>
</div>
