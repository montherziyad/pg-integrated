<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 font-semibold">Team Name</label>
        <input type="text" name="name" value="{{ old('name', $team->name) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div>
        <label class="block mb-2 font-semibold">Code</label>
        <input type="text" name="code" value="{{ old('code', $team->code) }}"
               class="w-full rounded-xl border-slate-300" required>
    </div>

    <div class="md:col-span-2">
        <label class="block mb-2 font-semibold">Description</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl border-slate-300">{{ old('description', $team->description) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $team->is_active ?? true))>
            <span class="font-semibold">Active</span>
        </label>
    </div>

</div>