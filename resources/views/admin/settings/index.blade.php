<x-app-layout>
    <x-slot name="header">System Settings</x-slot>

    <div class="space-y-6">
        <div>
            <h2 class="pg-title">System Settings</h2>
            <p class="pg-subtitle mt-1">Manage company and operational defaults.</p>
        </div>

        @include('admin.partials.nav')

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pg-card">
                <div class="pg-card-body">
                    <h3 class="text-lg font-bold mb-6">Company Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block mb-2 font-semibold">Company Name</label><input name="company_name" value="{{ old('company_name', $settings['company_name']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div><label class="block mb-2 font-semibold">Company Email</label><input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}" class="w-full rounded-xl border-slate-300"></div>
                        <div><label class="block mb-2 font-semibold">Company Phone</label><input name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}" class="w-full rounded-xl border-slate-300"></div>
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Outlook Email Intake</h3>
                            <p class="pg-subtitle mt-1">Microsoft Graph connection, validation rules and Traffic reviewers.</p>
                        </div>
                        <span class="pg-badge {{ $outlookSecretConfigured ? 'pg-badge-completed' : 'pg-badge-review' }}">
                            Client Secret {{ $outlookSecretConfigured ? 'Configured' : 'Missing' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2"><label class="inline-flex items-center gap-2"><input type="checkbox" name="outlook_enabled" value="1" @checked(old('outlook_enabled', $settings['outlook_enabled']))><span class="font-semibold">Enable Outlook intake</span></label></div>
                        <div><label class="block mb-2 font-semibold">Microsoft Tenant ID</label><input name="outlook_tenant_id" value="{{ old('outlook_tenant_id', $settings['outlook_tenant_id']) }}" class="w-full rounded-xl border-slate-300"></div>
                        <div><label class="block mb-2 font-semibold">Application Client ID</label><input name="outlook_client_id" value="{{ old('outlook_client_id', $settings['outlook_client_id']) }}" class="w-full rounded-xl border-slate-300"></div>
                        <div><label class="block mb-2 font-semibold">Monitored Mailbox</label><input type="email" name="outlook_mailbox_address" value="{{ old('outlook_mailbox_address', $settings['outlook_mailbox_address']) }}" class="w-full rounded-xl border-slate-300" placeholder="traffic@pgintegrated.com"></div>
                        <div><label class="block mb-2 font-semibold">Approved Company Email Domains</label><input name="outlook_company_domain" value="{{ old('outlook_company_domain', $settings['outlook_company_domain']) }}" class="w-full rounded-xl border-slate-300" placeholder="pgintegrated.com, mediazone.com" required><p class="mt-1 text-xs text-slate-500">Separate multiple domains with commas.</p></div>
                        <div><label class="block mb-2 font-semibold">Job Number Pattern</label><input name="outlook_job_number_pattern" value="{{ old('outlook_job_number_pattern', $settings['outlook_job_number_pattern']) }}" class="w-full rounded-xl border-slate-300 font-mono" required></div>
                        <div><label class="block mb-2 font-semibold">Maximum Attachment Size (MB)</label><input type="number" min="1" max="150" name="outlook_max_attachment_mb" value="{{ old('outlook_max_attachment_mb', $settings['outlook_max_attachment_mb']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div class="md:col-span-2"><label class="block mb-2 font-semibold">Allowed Brief Extensions</label><textarea name="outlook_allowed_extensions" rows="2" class="w-full rounded-xl border-slate-300">{{ old('outlook_allowed_extensions', $settings['outlook_allowed_extensions']) }}</textarea></div>
                    </div>

                    <div class="mt-8">
                        <h4 class="font-bold mb-3">Traffic Members</h4>
                        <p class="pg-subtitle mb-4">At least one selected member must appear in To or CC.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                            @foreach($users as $user)
                                <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3">
                                    <input type="checkbox" name="traffic_member_ids[]" value="{{ $user->id }}" @checked(in_array($user->id, old('traffic_member_ids', $trafficMemberIds)))>
                                    <span><strong class="block">{{ $user->name }}</strong><span class="text-xs text-slate-500">{{ $user->email }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                        Webhook URL: <code>{{ url('/api/outlook/webhook') }}</code><br>
                        Store <code>OUTLOOK_CLIENT_SECRET</code> and <code>OUTLOOK_WEBHOOK_CLIENT_STATE</code> in <code>.env</code>.
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <h3 class="text-lg font-bold mb-6">Operational Defaults</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block mb-2 font-semibold">Timezone</label><input name="timezone" value="{{ old('timezone', $settings['timezone']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div><label class="block mb-2 font-semibold">Date Format</label><select name="date_format" class="w-full rounded-xl border-slate-300">@foreach(['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y'] as $format)<option value="{{ $format }}" @selected(old('date_format', $settings['date_format']) === $format)>{{ $format }}</option>@endforeach</select></div>
                        <div><label class="block mb-2 font-semibold">Default Capacity Hours</label><input type="number" min="1" max="24" name="default_capacity_hours" value="{{ old('default_capacity_hours', $settings['default_capacity_hours']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div><label class="block mb-2 font-semibold">Job Number Prefix</label><input name="job_number_prefix" value="{{ old('job_number_prefix', $settings['job_number_prefix']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div class="md:col-span-2"><label class="inline-flex items-center gap-2"><input type="checkbox" name="email_notifications" value="1" @checked(old('email_notifications', $settings['email_notifications']))><span class="font-semibold">Enable email notifications</span></label></div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="pg-btn-primary">Save Settings</button>
            </div>
        </form>

        <div class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-lg font-bold">Outlook Connection Actions</h3>
                <p class="pg-subtitle mt-1">Save settings before testing or creating the webhook subscription.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.settings.outlook.test') }}">@csrf<button class="pg-btn-secondary">Test Connection</button></form>
                    <form method="POST" action="{{ route('admin.settings.outlook.subscribe') }}">@csrf<button class="pg-btn-primary">Create / Renew Subscription</button></form>
                </div>
                <div class="mt-4 text-sm text-slate-500">
                    Subscription ID: {{ $settings['outlook_subscription_id'] ?? 'Not created' }}<br>
                    Expires: {{ $settings['outlook_subscription_expires_at'] ?? '-' }}
                </div>
            </div>
        </div>

        @if(Auth::user()?->role?->code === 'SUPER_ADMIN')
            <div class="pg-card border border-red-200">
                <div class="pg-card-body">
                    <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-red-700">Test Data Reset</h3>
                            <p class="pg-subtitle mt-1">
                                Super Admin only. Use this before real testing. This does not delete system settings, roles, branches, categories, or the current Super Admin account.
                            </p>
                        </div>
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-red-700">Danger Zone</span>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.settings.reset-test-data') }}"
                        class="mt-6 space-y-5"
                        x-data="{
                            labels: {
                                email_intake: 'Intake Email',
                                jobs: 'Jobs',
                                clients: 'Clients',
                                teams: 'Team',
                                users: 'User'
                            },
                            confirmReset(event) {
                                const selected = Array.from(event.target.querySelectorAll('input[name=&quot;reset_targets[]&quot;]:checked')).map((input) => input.value);

                                if (! selected.length) {
                                    alert('Select at least one reset target.');
                                    event.preventDefault();
                                    return false;
                                }

                                if (event.target.querySelector('input[name=&quot;confirmation_phrase&quot;]').value !== 'RESET TEST DATA') {
                                    alert('Type RESET TEST DATA before continuing.');
                                    event.preventDefault();
                                    return false;
                                }

                                if (! confirm('This will clear database records for: ' + selected.map((item) => this.labels[item]).join(', ') + '. Are you sure?')) {
                                    event.preventDefault();
                                    return false;
                                }

                                for (const item of selected) {
                                    if (! confirm('You are about to delete ' + this.labels[item] + ' records. Are you sure?')) {
                                        event.preventDefault();
                                        return false;
                                    }
                                }

                                return true;
                            }
                        }"
                        @submit="confirmReset($event)"
                    >
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
                            <input
                                name="confirmation_phrase"
                                placeholder="RESET TEST DATA"
                                class="mt-2 w-full rounded-xl border-red-200 font-mono"
                                autocomplete="off"
                            >
                            <p class="mt-2 text-xs text-red-600">The reset will not run unless this phrase is exactly: RESET TEST DATA</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="rounded-xl bg-red-600 px-5 py-3 font-bold text-white hover:bg-red-700">
                                Clear selected test data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
