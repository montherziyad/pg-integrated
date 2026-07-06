@php
    $pageTitle = 'Join Us | PG Integrated';
    $description = 'Join PG Integrated and work with a team connecting strategy, creative, digital, production, and delivery operations.';
    $navUrl = function (array $item): string {
        if (! empty($item['url'])) {
            return url($item['url']);
        }

        if (! empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])) {
            return route($item['route']);
        }

        return '#';
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <title>{{ $pageTitle }}</title>
    <link rel="icon" href="{{ asset('prd-assets/images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .pg-site-desktop-nav,
        .pg-site-desktop-actions {
            display: none;
        }

        .pg-site-mobile-menu {
            display: block;
        }

        @media (min-width: 1180px) {
            .pg-site-desktop-nav,
            .pg-site-desktop-actions {
                display: flex;
            }

            .pg-site-mobile-menu {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-[#f7f3ec] text-slate-950 antialiased">
    <header class="sticky top-0 z-40 border-b border-black/10 bg-[#f7f3ec]/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3 lg:px-8">
            <a href="{{ route('website.home') }}" class="flex min-w-0 items-center gap-3" aria-label="PG Integrated home">
                <span class="flex h-12 w-24 shrink-0 items-center overflow-hidden sm:w-28">
                    <img src="{{ asset('prd-assets/PGi-Logo.png') }}" alt="PG Integrated" class="block w-auto object-contain" style="max-height: 44px; height: 44px;">
                </span>
                <span class="hidden truncate text-sm font-extrabold uppercase tracking-[.22em] 2xl:inline">PG Integrated</span>
            </a>

            <nav class="pg-site-desktop-nav items-center gap-4 text-xs font-bold uppercase tracking-[.12em] text-slate-700 2xl:gap-6 2xl:text-sm">
                @foreach ($navigationPages as $item)
                    <a href="{{ $navUrl($item) }}" class="{{ ($item['route'] ?? null) === 'careers.index' ? 'text-amber-700' : 'hover:text-amber-600' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="pg-site-desktop-actions items-center gap-2">
                <a href="{{ route('client.login') }}" class="rounded-full border border-black/15 px-4 py-2 text-sm font-bold hover:bg-white">Client</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-bold text-white">Dashboard</a>
                @else
                    <a href="{{ route('employee.login') }}" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-bold text-white">Employee</a>
                @endauth
            </div>

            <details class="pg-site-mobile-menu group relative">
                <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-black/15 bg-white/70 px-4 py-3 text-sm font-black uppercase tracking-[.16em] text-slate-950 shadow-sm transition hover:bg-white">
                    <span>Menu</span>
                    <span class="relative h-4 w-5">
                        <span class="absolute left-0 top-0 h-0.5 w-5 rounded-full bg-slate-950 transition group-open:top-2 group-open:rotate-45"></span>
                        <span class="absolute left-0 top-2 h-0.5 w-5 rounded-full bg-slate-950 transition group-open:opacity-0"></span>
                        <span class="absolute left-0 top-4 h-0.5 w-5 rounded-full bg-slate-950 transition group-open:top-2 group-open:-rotate-45"></span>
                    </span>
                </summary>

                <div class="absolute right-0 mt-3 w-[min(88vw,360px)] overflow-hidden rounded-[1.75rem] border border-black/10 bg-white p-3 shadow-2xl">
                    <nav class="grid gap-1">
                        @foreach ($navigationPages as $item)
                            <a href="{{ $navUrl($item) }}" class="rounded-2xl px-4 py-3 text-sm font-black uppercase tracking-[.14em] {{ ($item['route'] ?? null) === 'careers.index' ? 'bg-amber-50 text-amber-700' : 'text-slate-700 hover:bg-slate-50 hover:text-amber-700' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-3 grid gap-2 border-t border-black/10 pt-3">
                        <a href="{{ route('client.login') }}" class="rounded-2xl border border-black/15 px-4 py-3 text-center text-sm font-black">Client Portal</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-center text-sm font-black text-white">Dashboard</a>
                        @else
                            <a href="{{ route('employee.login') }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-center text-sm font-black text-white">Employee Login</a>
                        @endauth
                    </div>
                </div>
            </details>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden">
            <div class="absolute -right-28 top-0 h-96 w-96 rounded-full bg-amber-300/50 blur-3xl"></div>
            <div class="absolute -left-28 bottom-0 h-80 w-80 rounded-full bg-orange-200/60 blur-3xl"></div>

            <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-5 py-20 lg:grid-cols-[1fr_.78fr] lg:px-8 lg:py-28">
                <div>
                    <p class="mb-6 text-xs font-black uppercase tracking-[.3em] text-amber-700">Careers</p>
                    <h1 class="max-w-5xl text-5xl font-black leading-[.93] tracking-[-.055em] sm:text-7xl lg:text-8xl">
                        Join the team building bold creative operations.
                    </h1>
                    <p class="mt-8 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl">
                        We are always looking for talented strategists, designers, developers, producers, and client service professionals.
                        Apply to an open role or send us your profile for future opportunities.
                    </p>
                    <div class="mt-10 flex flex-wrap gap-3">
                        <a href="#application-form" class="rounded-full bg-slate-950 px-7 py-3 font-bold text-white hover:bg-slate-800">
                            Apply now
                        </a>
                        <a href="{{ route('website.work') }}" class="rounded-full border border-black/20 px-7 py-3 font-bold hover:bg-white">
                            View our work
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="flex aspect-[4/3] w-full items-center justify-center rounded-[2.5rem] bg-slate-950 p-10 text-center text-5xl font-black leading-none text-white shadow-2xl">
                        Think.<br>Make.<br>Deliver.
                    </div>
                </div>
            </div>
        </section>

        @if($jobs->count())
            <section class="mx-auto max-w-7xl px-5 pb-6 lg:px-8">
                <div class="mb-8 grid gap-8 lg:grid-cols-2">
                    <div>
                        <p class="text-sm font-black uppercase tracking-[.26em] text-amber-700">Open roles</p>
                        <h2 class="mt-4 text-4xl font-black tracking-[-.04em] sm:text-6xl">Current opportunities.</h2>
                    </div>
                    <p class="max-w-xl text-lg leading-8 text-slate-600">
                        Choose an active role below, or submit a general application and our team will review your profile.
                    </p>
                </div>

                <div class="grid gap-px overflow-hidden rounded-[2rem] bg-black/10 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($jobs as $job)
                        <article class="bg-white/80 p-8">
                            <div class="mb-8 text-sm font-black text-amber-700">0{{ $loop->iteration }}</div>
                            <h3 class="text-2xl font-black">{{ $job->title }}</h3>
                            <div class="mt-3 flex flex-wrap gap-2 text-xs font-bold uppercase tracking-[.16em] text-slate-500">
                                @if($job->department)<span>{{ $job->department }}</span>@endif
                                @if($job->location)<span>· {{ $job->location }}</span>@endif
                                @if($job->employment_type)<span>· {{ $job->employment_type }}</span>@endif
                            </div>
                            @if($job->description)
                                <p class="mt-5 line-clamp-4 leading-7 text-slate-600">{{ $job->description }}</p>
                            @endif
                            <a href="#application-form" class="mt-8 inline-flex rounded-full border border-black/20 px-5 py-2 text-sm font-bold hover:bg-[#f7f3ec]">
                                Apply for this role
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section id="application-form" class="mx-auto grid max-w-7xl gap-10 px-5 py-24 lg:grid-cols-[.42fr_1fr] lg:px-8">
            <div>
                <p class="text-sm font-black uppercase tracking-[.26em] text-amber-700">Application</p>
                <h2 class="mt-4 text-4xl font-black tracking-[-.04em] sm:text-6xl">Tell us about you.</h2>
                <p class="mt-6 text-lg leading-8 text-slate-600">
                    Your application will be saved in the Careers section inside the admin dashboard.
                </p>
            </div>

            <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-black/10 lg:p-8">
                @if(session('status'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 font-semibold text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 font-semibold text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('careers.apply') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Position</label>
                        <select name="career_job_id" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500">
                            <option value="">General application</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" @selected(old('career_job_id') == $job->id)>{{ $job->title }}</option>
                            @endforeach
                        </select>
                        @error('career_job_id') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Full name</label>
                            <input name="full_name" value="{{ old('full_name') }}" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500" required>
                            @error('full_name') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500" required>
                            @error('email') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Phone</label>
                            <input name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500">
                            @error('phone') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Current title</label>
                            <input name="current_title" value="{{ old('current_title') }}" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500">
                            @error('current_title') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Portfolio URL</label>
                            <input name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://..." class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500">
                            @error('portfolio_url') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">LinkedIn URL</label>
                            <input name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://..." class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500">
                            @error('linkedin_url') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">CV / Resume</label>
                        <input type="file" name="cv" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 file:mr-4 file:rounded-full file:border-0 file:bg-slate-950 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white">
                        @error('cv') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Message</label>
                        <textarea name="message" rows="5" class="w-full rounded-2xl border border-black/10 bg-[#f7f3ec] px-4 py-3 text-slate-950 outline-none focus:border-amber-500">{{ old('message') }}</textarea>
                        @error('message') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button class="rounded-full bg-slate-950 px-8 py-3 font-bold text-white hover:bg-slate-800">
                        Submit application
                    </button>
                </form>
            </div>
        </section>
    </main>

    <footer class="mx-auto flex max-w-7xl flex-col gap-4 border-t border-black/10 px-5 py-10 text-sm font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
        <p>© {{ now()->year }} PG Integrated.</p>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('client.login') }}">Client portal</a>
            <a href="{{ route('employee.login') }}">Employee login</a>
            <a href="{{ route('website.contact') }}">Contact</a>
            <a href="{{ route('careers.index') }}">Join Us</a>
        </div>
    </footer>
</body>
</html>
