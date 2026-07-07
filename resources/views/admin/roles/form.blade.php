<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 font-semibold">Role Name</label>
        <input type="text" name="name" value="{{ old('name', $role->name) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Code</label>
        <input type="text" name="code" value="{{ old('code', $role->code) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 font-semibold">Description</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl border-slate-300">{{ old('description', $role->description) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $role->is_active ?? true))>
            <span class="font-semibold">Active</span>
        </label>
    </div>

</div>


<div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
    <div class="mb-5 flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Screen Access Permissions</h3>
            <p class="mt-1 text-sm text-slate-500">Choose which dashboard screens this role can see and open. Super Admin and General Manager keep full access.</p>
        </div>
    </div>

    @php
        $currentPermissions = old('screen_permissions', $role->screen_permissions ?: \App\Support\RoleScreenPermissions::defaultsForRole($role->code));
    @endphp

    <div class="space-y-6">
        @foreach($screenPermissionGroups ?? [] as $group => $screens)
            <div>
                <div class="mb-3 text-xs font-black uppercase tracking-[.22em] text-slate-500">{{ $group }}</div>
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($screens as $key => $screen)
                        <label class="flex gap-3 rounded-2xl border border-slate-200 bg-white p-4 hover:border-slate-300">
                            <input type="checkbox" name="screen_permissions[{{ $key }}]" value="1" class="mt-1 rounded border-slate-300"
                                   @checked((bool) data_get($currentPermissions, $key, false))>
                            <span>
                                <span class="block font-bold text-slate-900">{{ $screen['label'] }}</span>
                                <span class="mt-1 block text-sm text-slate-500">{{ $screen['description'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
