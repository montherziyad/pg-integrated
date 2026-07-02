<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div><label class="block mb-2 font-semibold">Category Name</label><input name="name" value="{{ old('name', $category->name) }}" class="w-full rounded-xl border-slate-300" required></div>
    <div><label class="block mb-2 font-semibold">Code</label><input name="code" value="{{ old('code', $category->code) }}" class="w-full rounded-xl border-slate-300" required></div>
    <div class="md:col-span-2"><label class="block mb-2 font-semibold">Description</label><textarea name="description" rows="4" class="w-full rounded-xl border-slate-300">{{ old('description', $category->description) }}</textarea></div>
    <div class="md:col-span-2"><label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))><span class="font-semibold">Active</span></label></div>
</div>
