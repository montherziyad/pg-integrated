<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Pending | PG Integrated</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f2eb] font-sans text-slate-950 antialiased">
    <main class="mx-auto flex min-h-screen max-w-2xl items-center px-6 py-12">
        <section class="rounded-[2rem] bg-white p-8 text-center shadow-xl shadow-black/5">
            <p class="text-sm font-bold uppercase tracking-[.24em] text-slate-500">Pending approval</p>
            <h1 class="mt-4 text-4xl font-extrabold">Your company registration was received.</h1>
            <p class="mt-4 leading-7 text-slate-500">We sent a verification link to your email. Please verify your email first. After verification, PG Integrated will review your information and activate your client portal.</p>
            @if(session('status'))<div class="mt-5 rounded-xl bg-green-50 px-4 py-3 text-green-700">{{ session('status') }}</div>@endif
            <form method="POST" action="{{ route('client.verification.send') }}" class="mt-6 space-y-3">
                @csrf
                <input type="email" name="email" placeholder="Enter your email to resend verification" class="w-full rounded-2xl border-slate-300 px-4 py-3" required>
                <button class="w-full rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white">Resend verification email</button>
            </form>
            <a href="{{ route('client.login') }}" class="mt-8 inline-flex rounded-2xl bg-slate-950 px-6 py-3 font-bold text-white">Back to login</a>
        </section>
    </main>
</body>
</html>
