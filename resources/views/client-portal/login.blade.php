<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Client Portal | PG Integrated</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f2eb] font-sans text-slate-950 antialiased">
    <main class="mx-auto grid min-h-screen max-w-7xl items-center gap-16 px-6 py-12 lg:grid-cols-2 lg:px-10">
        <section>
            <a href="{{ route('website.home') }}" class="inline-flex items-center gap-3 text-xl font-extrabold"><img src="{{ asset('prd-assets/PGi-Logo.png') }}" alt="PG Integrated" class="h-12 w-auto"> <span>PG Integrated</span></a>
            <p class="mt-20 text-sm font-bold uppercase tracking-[.24em] text-slate-500">Client portal</p>
            <h1 class="mt-5 text-5xl font-extrabold leading-none tracking-[-.04em] sm:text-7xl">Your projects.<br>Your progress.</h1>
            <p class="mt-7 max-w-xl text-lg leading-8 text-slate-600">Follow active projects, job progress, milestones, and delivery status from one secure view.</p>
        </section>

        <section class="rounded-[2rem] bg-white p-7 shadow-xl shadow-black/5 sm:p-10">
            <h2 class="text-3xl font-extrabold">Client sign in</h2>
            <p class="mt-2 text-slate-500">Use the portal credentials provided by your account manager.</p>
            <form method="POST" action="{{ route('client.login.store') }}" class="mt-8 space-y-5">
                @csrf
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-2xl border-slate-300 px-4 py-3">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-semibold">Password</span>
                    <input type="password" name="password" required class="w-full rounded-2xl border-slate-300 px-4 py-3">
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-slate-950">
                    Remember me
                </label>
                <button class="w-full rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white">Open portal</button>
            </form>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                New client or company?
                <a href="{{ route('client.register') }}" class="font-bold text-slate-950 underline">Register your company</a>
            </div>

            <p class="mt-7 border-t border-black/10 pt-6 text-sm text-slate-500">
                PG Integrated employee?
                <a href="{{ route('employee.login') }}" class="font-bold text-slate-950 underline">Employee login</a>
            </p>
        </section>
    </main>
</body>
</html>
