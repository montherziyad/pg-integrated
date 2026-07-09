<x-app-layout>
    <x-slot name="header">Test Data Reset</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title text-red-700">Test Data Reset</h2>
                <p class="pg-subtitle mt-1">Super Admin only. This page clears test records without deleting system settings, roles, branches, categories, or the current Super Admin account.</p>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="pg-btn-secondary">Back to Settings</a>
        </div>

        @include('admin.partials.nav')

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if(Auth::user()?->role?->code === 'SUPER_ADMIN')
            <div class="pg-card border border-red-200">
                <div class="pg-card-body">
                    <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-red-700">Danger Zone</h3>
                            <p class="pg-subtitle mt-1">Select exactly what you want to clear. The system will ask for confirmation for the whole action and for each selected section.</p>
                        </div>
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-red-700">Protected</span>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.reset-test-data') }}" class="mt-6 space-y-5" x-data="{
                        labels: { email_intake: 'Intake Email', jobs: 'Jobs', clients: 'Clients', teams: 'Team', users: 'User' },
                        confirmReset(event) {
                            const selected = Array.from(event.target.querySelectorAll('input[name=&quot;reset_targets[]&quot;]:checked')).map((input) => input.value);
                            if (! selected.length) { alert('Select at least one reset target.'); event.preventDefault(); return false; }
                            if (event.target.querySelector('input[name=&quot;confirmation_phrase&quot;]').value !== 'RESET TEST DATA') { alert('Type RESET TEST DATA before continuing.'); event.preventDefault(); return false; }
                            if (! confirm('This will clear database records for: ' + selected.map((item) => this.labels[item]).join(', ') + '. Are you sure?')) { event.preventDefault(); return false; }
                            for (const item of selected) { if (! confirm('You are about to delete ' + this.labels[item] + ' records. Are you sure?')) { event.preventDefault(); return false; } }
                            return true;
                        }
                    }" @submit="confirmReset($event)">
                        @csrf

                        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                            @foreach([
                                'email_intake' => ['label' => 'Intake Email', 'hint' => 'Outlook intake emails, validations, and attachments.'],
                                'jobs' => ['label' => 'Jobs', 'hint' => 'Jobs, assignments, assets, activities, approvals, and notifications.'],
                                'clients' => ['label' => 'Clients', 'hint' => 'Clients, projects, client service links, and client requests. Requires Jobs.'],
                                'teams' => ['label' => 'Team', 'hint' => 'Teams only. Requires Jobs because assignments use teams.'],
                                'users' => ['label' => 'User', 'hint' => 'Employees except current/Super Admin users. Requires Jobs and Clients.'],
                            ] as $value => $item)
                                <label class="rounded-2xl border border-red-100 bg-red-50/60 p-4">
                                    <span class="flex items-center gap-3">
                                        <input type="checkbox" name="reset_targets[]" value="{{ $value }}" class="rounded border-red-300">
                                        <span class="font-black text-red-950">{{ $item['label'] }}</span>
                                    </span>
                                    <span class="mt-2 block text-xs leading-5 text-red-700">{{ $item['hint'] }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="rounded-2xl border border-red-200 bg-white p-4">
                            <label class="block text-sm font-bold text-red-800">Type confirmation phrase</label>
                            <input name="confirmation_phrase" placeholder="RESET TEST DATA" class="mt-2 w-full rounded-xl border-red-200 font-mono" autocomplete="off">
                            <p class="mt-2 text-xs text-red-600">The reset will not run unless this phrase is exactly: RESET TEST DATA</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="rounded-xl bg-red-600 px-5 py-3 font-bold text-white hover:bg-red-700">Clear selected test data</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">Only Super Admin can access test data reset.</div>
        @endif
    </div>
</x-app-layout>
