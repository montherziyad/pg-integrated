<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 font-semibold">Branch Name</label>
        <input type="text" name="name" value="{{ old('name', $branch->name) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Code</label>
        <input type="text" name="code" value="{{ old('code', $branch->code) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Country</label>
        <input type="text" name="country" value="{{ old('country', $branch->country) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div>
        <label class="block mb-2 font-semibold">City</label>
        <input type="text" name="city" value="{{ old('city', $branch->city) }}"
               class="w-full rounded-xl border-slate-300">
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $branch->is_active ?? true))>
            <span class="font-semibold">Active</span>
        </label>
    </div>

</div>
