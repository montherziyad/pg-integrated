<x-app-layout>
    <x-slot name="header">Outlook Email Intake</x-slot>

    <div class="space-y-6">
        <div>
            <h2 class="pg-title">Outlook Email Intake</h2>
            <p class="pg-subtitle mt-1">Microsoft Graph connection, validation rules, and Traffic reviewers are managed separately for safety.</p>
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

        <form method="POST" action="{{ route('admin.settings.outlook.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="pg-card">
                <div class="pg-card-body">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Outlook Email Intake</h3>
                            <p class="pg-subtitle mt-1">Only edit this page when changing mailbox, validation, or traffic intake rules.</p>
                        </div>
                        <span class="pg-badge {{ $outlookSecretConfigured ? 'pg-badge-completed' : 'pg-badge-review' }}">
                            Client Secret {{ $outlookSecretConfigured ? 'Configured' : 'Missing' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <input type="hidden" name="outlook_enabled" value="0">
                            <label class="inline-flex items-center gap-2"><input type="checkbox" name="outlook_enabled" value="1" @checked(old('outlook_enabled', $settings['outlook_enabled']))><span class="font-semibold">Enable Outlook intake</span></label>
                        </div>
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

            <div class="flex justify-end">
                <button type="submit" class="pg-btn-primary">Save Outlook Intake</button>
            </div>
        </form>

        <div class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-lg font-bold">Outlook Connection Actions</h3>
                <p class="pg-subtitle mt-1">Save Outlook settings before testing or creating the webhook subscription.</p>
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
    </div>
</x-app-layout>
