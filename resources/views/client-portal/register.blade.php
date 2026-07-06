<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Company | PG Integrated</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f2eb] font-sans text-slate-950 antialiased">
    <main class="mx-auto max-w-4xl px-6 py-12">
        <a href="{{ route('client.login') }}" class="text-sm font-bold text-slate-500">← Back to client login</a>
        <section class="mt-8 rounded-[2rem] bg-white p-7 shadow-xl shadow-black/5 sm:p-10">
            <p class="text-sm font-bold uppercase tracking-[.24em] text-slate-500">Client registration</p>
            <h1 class="mt-4 text-4xl font-extrabold tracking-tight">Register your company</h1>
            <p class="mt-3 text-slate-500">Submit your company information. PG Integrated will review it and activate your portal when approved.</p>
            <form method="POST" action="{{ route('client.register.store') }}" class="mt-8 grid gap-5 md:grid-cols-2">
                @csrf
                <label class="block"><span class="mb-2 block text-sm font-semibold">Company name</span><input name="company_name" value="{{ old('company_name') }}" required class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('company_name')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Contact person</span><input name="contact_person" value="{{ old('contact_person') }}" required class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('contact_person')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Company email</span><input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('email')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Phone</span><input name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Industry</span><input name="industry" value="{{ old('industry') }}" class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Website / domain</span><input name="website" value="{{ old('website') }}" class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Country</span><input name="country" value="{{ old('country') }}" class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">City</span><input name="city" value="{{ old('city') }}" class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Password</span><input type="password" name="password" required class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('password')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Confirm password</span><input type="password" name="password_confirmation" required class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <label class="block md:col-span-2"><span class="mb-2 block text-sm font-semibold">Company profile / request summary</span><textarea name="company_profile" rows="5" class="w-full rounded-2xl border-slate-300 px-4 py-3">{{ old('company_profile') }}</textarea></label>
                <div class="md:col-span-2"><button class="w-full rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white">Submit for review</button></div>
            </form>
        </section>
    </main>
</body>
</html>
