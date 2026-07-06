<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Login | PG Integrated</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-sans text-white antialiased">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="relative hidden overflow-hidden p-12 lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -left-32 top-24 h-96 w-96 rounded-full bg-rose-400/30 blur-3xl"></div>
            <div class="absolute -right-20 bottom-12 h-96 w-96 rounded-full bg-amber-300/30 blur-3xl"></div>
            <a href="{{ route('website.home') }}" class="relative inline-flex h-14 w-28 items-center overflow-hidden" aria-label="PG Integrated home">
                <img src="{{ asset('prd-assets/PGi-Logo.png') }}" alt="PG Integrated" class="block w-auto object-contain" style="height: 48px; max-height: 48px;">
            </a>
            <div class="relative max-w-xl">
                <p class="mb-5 text-sm font-bold uppercase tracking-[.24em] text-amber-300">Employee workspace</p>
                <h1 class="text-6xl font-extrabold leading-[.95] tracking-[-.05em]">One place to move every project forward.</h1>
                <p class="mt-7 text-lg leading-8 text-slate-300">Access client work, studio operations, traffic, support, CRM, marketing, and reports.</p>
            </div>
            <p class="relative text-sm text-slate-400">Authorized PG Integrated employees only.</p>
        </section>

        <section class="flex items-center justify-center bg-[#f5f2eb] px-6 py-12 text-slate-950">
            <div class="w-full max-w-md">
                <a href="{{ route('website.home') }}" class="mb-12 inline-flex h-14 w-28 items-center overflow-hidden lg:hidden" aria-label="PG Integrated home">
                    <img src="{{ asset('prd-assets/PGi-Logo.png') }}" alt="PG Integrated" class="block w-auto object-contain" style="height: 48px; max-height: 48px;">
                </a>
                <p class="text-sm font-bold uppercase tracking-[.22em] text-slate-500">Team access</p>
                <h2 class="mt-4 text-4xl font-extrabold tracking-tight">Employee login</h2>
                <p class="mt-3 text-slate-600">Use your company account to enter the operations platform.</p>

                <x-auth-session-status class="mt-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf
                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Company email</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full rounded-2xl border-slate-300 bg-white px-4 py-3">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Password</span>
                        <input type="password" name="password" required autocomplete="current-password"
                               class="w-full rounded-2xl border-slate-300 bg-white px-4 py-3">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </label>
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-slate-950">
                            Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="font-semibold underline">Forgot password?</a>
                    </div>
                    <button class="w-full rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white">Enter dashboard</button>
                </form>

                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
                    New PG Integrated employee?
                    <a href="{{ route('employee.register') }}" class="font-bold text-slate-950 underline">Register employee account</a>
                </div>

                <div class="mt-8 border-t border-black/10 pt-6 text-sm text-slate-600">
                    Are you a client?
                    <a href="{{ route('client.login') }}" class="font-bold text-slate-950 underline">Open the client portal</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
