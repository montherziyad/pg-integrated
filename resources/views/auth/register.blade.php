<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Registration | PG Integrated</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f2eb] font-sans text-slate-950 antialiased">
    <main class="mx-auto flex min-h-screen max-w-2xl items-center px-6 py-12">
        <section class="w-full rounded-[2rem] bg-white p-8 shadow-xl shadow-black/5">
            <a href="{{ route('website.home') }}" class="inline-flex items-center gap-3 font-extrabold">
                <img src="{{ asset('prd-assets/PGi-Logo.png') }}" alt="PG Integrated" class="h-12 w-auto">
                <span>PG Integrated</span>
            </a>
            <p class="mt-10 text-sm font-bold uppercase tracking-[.24em] text-slate-500">Employee registration</p>
            <h1 class="mt-4 text-4xl font-extrabold tracking-tight">Create employee account</h1>
            <p class="mt-3 text-slate-500">Only @pgintegrated.com emails are accepted. After email verification, admin approval is required before access.</p>

            <form method="POST" action="{{ route('employee.register.store') }}" class="mt-8 space-y-5">
                @csrf
                <label class="block"><span class="mb-2 block text-sm font-semibold">Full name</span><input name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('name')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">PG Integrated email</span><input type="email" name="email" value="{{ old('email') }}" placeholder="name@pgintegrated.com" required class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('email')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Password</span><input type="password" name="password" required class="w-full rounded-2xl border-slate-300 px-4 py-3"><x-input-error :messages="$errors->get('password')" class="mt-2" /></label>
                <label class="block"><span class="mb-2 block text-sm font-semibold">Confirm password</span><input type="password" name="password_confirmation" required class="w-full rounded-2xl border-slate-300 px-4 py-3"></label>
                <button class="w-full rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white">Submit for approval</button>
            </form>

            <p class="mt-6 text-sm text-slate-500">Already registered? <a href="{{ route('employee.login') }}" class="font-bold text-slate-950 underline">Employee login</a></p>
        </section>
    </main>
</body>
</html>
