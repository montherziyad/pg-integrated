<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block mb-2 font-semibold">Client Name</label>
        <input type="text" name="name" value="{{ old('name', $client->name) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Client Code</label>
        <input type="text" name="client_code" value="{{ old('client_code', $client->client_code) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Branch</label>
        <select name="branch_id" class="w-full rounded-xl border-slate-300">
            <option value="">Select branch</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}"
                        @selected((string) old('branch_id', $client->branch_id) === (string) $branch->id)>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Account Manager</label>
        <select name="account_manager_id" class="w-full rounded-xl border-slate-300">
            <option value="">Select account manager</option>
            @foreach($accountManagers as $accountManager)
                <option value="{{ $accountManager->id }}"
                        @selected((string) old('account_manager_id', $client->account_manager_id) === (string) $accountManager->id)>
                    {{ $accountManager->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Industry</label>
        <input type="text" name="industry" value="{{ old('industry', $client->industry) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div>
        <label class="block mb-2 font-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $client->email) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div>
        <label class="block mb-2 font-semibold">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $client->phone) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div class="flex items-end pb-3">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $client->is_active ?? true))>
            <span class="font-semibold">Active</span>
        </label>
    </div>

    <div class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-5">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="portal_enabled" value="1"
                   @checked(old('portal_enabled', $client->portal_enabled ?? false))>
            <span class="font-semibold">Enable client portal</span>
        </label>
        <p class="mt-1 text-sm text-slate-500">The client will sign in with the email above and the password below.</p>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 font-semibold">Portal Password</label>
                <input type="password" name="password" class="w-full rounded-xl border-slate-300"
                       autocomplete="new-password">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-300"
                       autocomplete="new-password">
            </div>
        </div>
    </div>
</div>
