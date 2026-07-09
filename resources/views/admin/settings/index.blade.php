<x-app-layout>
    <x-slot name="header">System Settings</x-slot>

    <div class="space-y-6">
        <div>
            <h2 class="pg-title">System Settings</h2>
            <p class="pg-subtitle mt-1">General dashboard defaults only. Outlook intake has its own protected page.</p>
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
                    <h3 class="text-lg font-bold mb-2">Platform Defaults</h3>
                    <p class="pg-subtitle mb-6">Theme, language, dashboard logo, and API panels can be extended here later without touching Outlook.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block mb-2 font-semibold">Timezone</label><input name="timezone" value="{{ old('timezone', $settings['timezone']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div><label class="block mb-2 font-semibold">Date Format</label><select name="date_format" class="w-full rounded-xl border-slate-300">@foreach(['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y'] as $format)<option value="{{ $format }}" @selected(old('date_format', $settings['date_format']) === $format)>{{ $format }}</option>@endforeach</select></div>
                        <div><label class="block mb-2 font-semibold">Default Capacity Hours</label><input type="number" min="1" max="24" name="default_capacity_hours" value="{{ old('default_capacity_hours', $settings['default_capacity_hours']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div><label class="block mb-2 font-semibold">Job Number Prefix</label><input name="job_number_prefix" value="{{ old('job_number_prefix', $settings['job_number_prefix']) }}" class="w-full rounded-xl border-slate-300" required></div>
                        <div class="md:col-span-2">
                            <input type="hidden" name="email_notifications" value="0">
                            <label class="inline-flex items-center gap-2"><input type="checkbox" name="email_notifications" value="1" @checked(old('email_notifications', $settings['email_notifications']))><span class="font-semibold">Enable email notifications</span></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <h3 class="text-lg font-bold mb-2">Branding & Appearance</h3>
                    <p class="pg-subtitle mb-6">Logo, dashboard color, light/dark preference, and the default interface language.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 font-semibold">Dashboard logo path / URL</label>
                            <input name="dashboard_logo_path" value="{{ old('dashboard_logo_path', $settings['dashboard_logo_path']) }}" placeholder="/prd-assets/PGi-Logo.png" class="w-full rounded-xl border-slate-300">
                            <p class="mt-1 text-xs text-slate-500">Use a public asset path or external image URL. File upload can be added later.</p>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Primary brand color</label>
                            <input name="brand_primary_color" value="{{ old('brand_primary_color', $settings['brand_primary_color']) }}" placeholder="#020617" class="w-full rounded-xl border-slate-300">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Appearance mode</label>
                            <select name="appearance_mode" class="w-full rounded-xl border-slate-300">
                                @foreach(['light' => 'Light', 'dark' => 'Dark', 'system' => 'Follow system'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('appearance_mode', $settings['appearance_mode']) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Default language</label>
                            <select name="default_language" class="w-full rounded-xl border-slate-300">
                                <option value="en" @selected(old('default_language', $settings['default_language']) === 'en')>English</option>
                                <option value="ar" @selected(old('default_language', $settings['default_language']) === 'ar')>Arabic</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">Translation provider</label>
                            <select name="translation_provider" class="w-full rounded-xl border-slate-300">
                                <option value="manual" @selected(old('translation_provider', $settings['translation_provider']) === 'manual')>Manual content / CMS</option>
                                <option value="openai" @selected(old('translation_provider', $settings['translation_provider']) === 'openai')>OpenAI / ChatGPT ready</option>
                            </select>
                            <p class="mt-1 text-xs text-slate-500">OpenAI can be connected after adding the API key securely in environment settings.</p>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">OpenAI translation model</label>
                            <input name="openai_translation_model" value="{{ old('openai_translation_model', $settings['openai_translation_model']) }}" placeholder="gpt-4.1-mini" class="w-full rounded-xl border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pg-card">
                <div class="pg-card-body">
                    <h3 class="text-lg font-bold mb-2">Dropbox / WeTransfer API Panels</h3>
                    <p class="pg-subtitle mb-6">Prepared integration settings for future automatic uploads, link storage, and delivery tracking.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <input type="hidden" name="dropbox_api_enabled" value="0">
                            <label class="inline-flex items-center gap-2 font-semibold">
                                <input type="checkbox" name="dropbox_api_enabled" value="1" @checked(old('dropbox_api_enabled', $settings['dropbox_api_enabled']))>
                                Enable Dropbox API panel
                            </label>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold">Dropbox app key</label>
                                    <input name="dropbox_app_key" value="{{ old('dropbox_app_key', $settings['dropbox_app_key']) }}" class="w-full rounded-xl border-slate-300">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold">Default Dropbox folder</label>
                                    <input name="dropbox_default_folder" value="{{ old('dropbox_default_folder', $settings['dropbox_default_folder']) }}" placeholder="/PG Integrated/Deliveries" class="w-full rounded-xl border-slate-300">
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <input type="hidden" name="wetransfer_api_enabled" value="0">
                            <label class="inline-flex items-center gap-2 font-semibold">
                                <input type="checkbox" name="wetransfer_api_enabled" value="1" @checked(old('wetransfer_api_enabled', $settings['wetransfer_api_enabled']))>
                                Enable WeTransfer API panel
                            </label>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold">Default sender email</label>
                                    <input type="email" name="wetransfer_default_email" value="{{ old('wetransfer_default_email', $settings['wetransfer_default_email']) }}" placeholder="mziyad@pgintegrated.com" class="w-full rounded-xl border-slate-300">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold">Default delivery message</label>
                                    <textarea name="wetransfer_default_message" rows="3" class="w-full rounded-xl border-slate-300" placeholder="Final files are ready for your review.">{{ old('wetransfer_default_message', $settings['wetransfer_default_message']) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="pg-btn-primary">Save Settings</button>
            </div>
        </form>

        @if(Auth::user()?->role?->code === 'SUPER_ADMIN')
            <div class="pg-card border border-red-200">
                <div class="pg-card-body flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-red-700">Test Data Reset</h3>
                        <p class="pg-subtitle mt-1">Moved to a separate protected page so it is not mixed with regular settings.</p>
                    </div>
                    <a href="{{ route('admin.settings.reset-test-data.index') }}" class="rounded-xl bg-red-600 px-5 py-3 font-bold text-white hover:bg-red-700">Open Test Data Reset</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
