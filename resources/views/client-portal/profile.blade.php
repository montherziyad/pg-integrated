<x-dynamic-component component="client-portal.layout" :client="$client" title="Profile">
    <h1 class="text-4xl font-extrabold">Profile & company information</h1>
    <p class="mt-2 text-slate-500">Update the client file used by PG Integrated for briefs, projects, and campaign planning.</p>

    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="mt-8 rounded-3xl bg-white p-6">
        @csrf
        @method('PATCH')
        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Contact person</span>
                <input name="contact_person" value="{{ old('contact_person', $client->contact_person) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Company name</span>
                <input name="company_name" value="{{ old('company_name', $client->company_name) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Industry</span>
                <input name="industry" value="{{ old('industry', $client->industry) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Phone</span>
                <input name="phone" value="{{ old('phone', $client->phone) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Country</span>
                <input name="country" value="{{ old('country', $client->country) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">City</span>
                <input name="city" value="{{ old('city', $client->city) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Website</span>
                <input name="website" value="{{ old('website', $client->website) }}" class="w-full rounded-xl border-slate-300">
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-semibold">Profile image</span>
                <input type="file" name="avatar" accept="image/*" class="w-full rounded-xl border border-slate-300 bg-white p-2">
            </label>
            <label class="block md:col-span-2">
                <span class="mb-2 block text-sm font-semibold">Company profile</span>
                <textarea name="company_profile" rows="5" class="w-full rounded-xl border-slate-300">{{ old('company_profile', $client->company_profile) }}</textarea>
            </label>
        </div>
        <button class="mt-6 rounded-xl bg-slate-950 px-6 py-3 font-bold text-white">Save profile</button>
    </form>
</x-dynamic-component>
