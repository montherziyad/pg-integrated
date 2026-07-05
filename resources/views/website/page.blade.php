@php
    $type = data_get($content, 'type', 'home');
    $hero = data_get($content, 'hero', []);
    $title = data_get($seo, 'title', $page?->title ?? data_get($hero, 'title', 'PG Integrated'));
    $description = data_get($seo, 'description', data_get($hero, 'body', 'PG Integrated creative, digital, production, and client operations.'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>
    <link rel="icon" href="{{ asset('prd-assets/images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f3ec] text-slate-950 antialiased">
    <header class="sticky top-0 z-40 border-b border-black/10 bg-[#f7f3ec]/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ route('website.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('prd-assets/PGi-Logo.png') }}" alt="PG Integrated" class="h-11 w-auto">
                <span class="hidden text-sm font-extrabold uppercase tracking-[.22em] sm:inline">PG Integrated</span>
            </a>
            <nav class="hidden items-center gap-6 text-sm font-bold uppercase tracking-[.12em] text-slate-700 lg:flex">
                @foreach ($navigationPages as $item)
                    <a href="{{ route($item['route']) }}" class="hover:text-amber-600">{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <div class="flex items-center gap-2">
                @auth
                    @if ($page?->exists)
                        <a href="{{ route('admin.cms.edit', $page) }}" class="hidden rounded-full border border-amber-400 bg-amber-300 px-4 py-2 text-sm font-bold text-slate-950 hover:bg-amber-200 sm:inline-flex">Edit this page</a>
                    @endif
                @endauth
                <a href="{{ route('client.login') }}" class="rounded-full border border-black/15 px-4 py-2 text-sm font-bold hover:bg-white">Client</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-bold text-white">Dashboard</a>
                @else
                    <a href="{{ route('employee.login') }}" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-bold text-white">Employee</a>
                @endauth
            </div>
        </div>
        <nav class="flex gap-4 overflow-x-auto px-5 pb-4 text-xs font-bold uppercase tracking-[.12em] text-slate-600 lg:hidden">
            @foreach ($navigationPages as $item)
                <a href="{{ route($item['route']) }}" class="shrink-0">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </header>

    <main>
        @auth
            @if ($page?->exists)
                <div class="fixed bottom-5 right-5 z-50 lg:hidden">
                    <a href="{{ route('admin.cms.edit', $page) }}" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-black text-slate-950 shadow-2xl">Edit page</a>
                </div>
            @endif
        @endauth

        <section class="relative overflow-hidden">
            <div class="absolute -right-28 top-0 h-96 w-96 rounded-full bg-amber-300/50 blur-3xl"></div>
            <div class="absolute -left-28 bottom-0 h-80 w-80 rounded-full bg-orange-200/60 blur-3xl"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-5 py-20 lg:grid-cols-[1fr_.78fr] lg:px-8 lg:py-28">
                <div>
                    <p class="mb-6 text-xs font-black uppercase tracking-[.3em] text-amber-700">{{ data_get($hero, 'eyebrow', 'PG Integrated') }}</p>
                    <h1 class="max-w-5xl text-5xl font-black leading-[.93] tracking-[-.055em] sm:text-7xl lg:text-8xl">
                        {{ data_get($hero, 'title', 'PG Integrated') }}
                    </h1>
                    <p class="mt-8 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl">{{ data_get($hero, 'body') }}</p>
                    <div class="mt-10 flex flex-wrap gap-3">
                        @if (data_get($hero, 'primary_label'))
                            <a href="{{ data_get($hero, 'primary_url', route('website.contact')) }}" class="rounded-full bg-slate-950 px-7 py-3 font-bold text-white hover:bg-slate-800">
                                {{ data_get($hero, 'primary_label') }}
                            </a>
                        @endif
                        @if (data_get($hero, 'secondary_label'))
                            <a href="{{ data_get($hero, 'secondary_url', route('website.services')) }}" class="rounded-full border border-black/20 px-7 py-3 font-bold hover:bg-white">
                                {{ data_get($hero, 'secondary_label') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    @if (data_get($hero, 'video_url'))
                        <div class="aspect-video overflow-hidden rounded-[2.5rem] bg-slate-950 shadow-2xl">
                            @if (str_contains(data_get($hero, 'video_url'), 'youtube') || str_contains(data_get($hero, 'video_url'), 'youtu.be') || str_contains(data_get($hero, 'video_url'), 'vimeo'))
                                <iframe src="{{ data_get($hero, 'video_url') }}" class="h-full w-full" allowfullscreen></iframe>
                            @else
                                <video src="{{ data_get($hero, 'video_url') }}" class="h-full w-full object-cover" controls></video>
                            @endif
                        </div>
                    @elseif (data_get($hero, 'image'))
                        <img src="{{ data_get($hero, 'image') }}" alt="" class="aspect-[4/3] w-full rounded-[2.5rem] object-cover shadow-2xl">
                    @else
                        <div class="flex aspect-[4/3] w-full items-center justify-center rounded-[2.5rem] bg-slate-950 p-10 text-center text-5xl font-black leading-none text-white shadow-2xl">
                            Think.<br>Make.<br>Deliver.
                        </div>
                    @endif
                </div>
            </div>
        </section>

        @if ($stats = data_get($content, 'stats'))
            <section class="mx-auto grid max-w-7xl gap-px overflow-hidden rounded-[2rem] bg-black/10 px-5 md:grid-cols-3 lg:px-8">
                @foreach ($stats as $stat)
                    <div class="bg-white/70 p-8">
                        <div class="text-5xl font-black tracking-tight">{{ data_get($stat, 'value') }}</div>
                        <div class="mt-2 text-sm font-bold uppercase tracking-[.18em] text-slate-500">{{ data_get($stat, 'label') }}</div>
                    </div>
                @endforeach
            </section>
        @endif

        @if ($intro = data_get($content, 'intro'))
            <section class="mx-auto grid max-w-7xl gap-10 px-5 py-24 lg:grid-cols-[.45fr_1fr] lg:px-8">
                <p class="text-sm font-black uppercase tracking-[.26em] text-amber-700">{{ data_get($intro, 'eyebrow') }}</p>
                <div>
                    <h2 class="text-4xl font-black tracking-[-.04em] sm:text-6xl">{{ data_get($intro, 'title') }}</h2>
                    <p class="mt-8 max-w-3xl text-lg leading-8 text-slate-600">{{ data_get($intro, 'body') }}</p>
                </div>
            </section>
        @endif

        @if ($services = data_get($content, 'services'))
            <section class="bg-slate-950 py-24 text-white">
                <div class="mx-auto max-w-7xl px-5 lg:px-8">
                    <div class="grid gap-8 lg:grid-cols-2">
                        <h2 class="text-4xl font-black tracking-[-.04em] sm:text-6xl">{{ data_get($services, 'title') }}</h2>
                        <p class="max-w-xl text-lg leading-8 text-slate-300">{{ data_get($services, 'body') }}</p>
                    </div>
                    <div class="mt-16 grid gap-px overflow-hidden rounded-[2rem] bg-white/15 md:grid-cols-3">
                        @foreach (data_get($services, 'items', []) as $service)
                            <article class="bg-slate-950 p-8">
                                <div class="mb-10 text-sm font-black text-amber-300">0{{ $loop->iteration }}</div>
                                <h3 class="text-2xl font-black">{{ data_get($service, 'title') }}</h3>
                                <p class="mt-4 leading-7 text-slate-400">{{ data_get($service, 'body') }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($type === 'services' || data_get($content, 'items') && $type === 'content')
            <section class="mx-auto grid max-w-7xl gap-6 px-5 py-24 md:grid-cols-3 lg:px-8">
                @foreach (data_get($content, 'items', []) as $item)
                    <article class="rounded-[2rem] bg-white p-8 shadow-sm">
                        <h2 class="text-2xl font-black">{{ data_get($item, 'title') }}</h2>
                        <p class="mt-4 leading-7 text-slate-600">{{ data_get($item, 'body') }}</p>
                    </article>
                @endforeach
            </section>
        @endif

        @if ($portfolio = data_get($content, 'portfolio'))
            <x-website.portfolio-grid :title="data_get($portfolio, 'title')" :items="data_get($portfolio, 'items', [])" />
        @endif

        @if ($type === 'portfolio')
            <x-website.portfolio-grid :title="'Portfolio'" :items="data_get($content, 'items', [])" />
        @endif

        @if ($type === 'team')
            <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
                <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                    @foreach (data_get($content, 'items', []) as $member)
                        <article class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
                            @if (data_get($member, 'video_url'))
                                <video src="{{ data_get($member, 'video_url') }}" class="aspect-[4/4] w-full object-cover" controls></video>
                            @else
                                <img src="{{ data_get($member, 'image') }}" alt="{{ data_get($member, 'name') }}" class="aspect-[4/4] w-full object-cover">
                            @endif
                            <div class="p-6">
                                <h2 class="text-2xl font-black">{{ data_get($member, 'name') }}</h2>
                                <p class="mt-2 font-semibold text-amber-700">{{ data_get($member, 'role') }}</p>
                                @if (data_get($member, 'email'))
                                    <a href="mailto:{{ data_get($member, 'email') }}" class="mt-3 inline-block text-sm font-semibold text-slate-500">{{ data_get($member, 'email') }}</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($type === 'clients')
            <section class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
                <div class="grid gap-px overflow-hidden rounded-[2rem] bg-black/10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach (data_get($content, 'items', []) as $client)
                        <article class="flex min-h-40 items-center justify-center bg-white p-8">
                            <img src="{{ data_get($client, 'image') }}" alt="{{ data_get($client, 'name') }}" class="max-h-24 max-w-full object-contain">
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($type === 'contact')
            <section class="mx-auto grid max-w-7xl gap-8 px-5 py-24 lg:grid-cols-2 lg:px-8">
                <div class="rounded-[2rem] bg-white p-8 shadow-sm">
                    <h2 class="text-3xl font-black">Contact details</h2>
                    <div class="mt-8 space-y-5">
                        @foreach (data_get($content, 'contacts', []) as $contact)
                            <div>
                                <p class="text-xs font-black uppercase tracking-[.24em] text-slate-400">{{ data_get($contact, 'label') }}</p>
                                @if (data_get($contact, 'url'))
                                    <a href="{{ data_get($contact, 'url') }}" class="mt-1 inline-block text-xl font-black hover:text-amber-700">{{ data_get($contact, 'value') }}</a>
                                @else
                                    <p class="mt-1 text-xl font-black">{{ data_get($contact, 'value') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-sm">
                    <h2 class="text-3xl font-black">Locations</h2>
                    <div class="mt-8 grid gap-5">
                        @foreach (data_get($content, 'locations', []) as $location)
                            <article class="rounded-2xl border border-white/10 p-5">
                                <h3 class="text-2xl font-black">{{ data_get($location, 'city') }}</h3>
                                <p class="mt-2 text-slate-300">{{ data_get($location, 'address') }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($cta = data_get($content, 'cta'))
            <section class="px-5 pb-10 lg:px-8">
                <div class="mx-auto max-w-7xl rounded-[2.5rem] bg-amber-300 px-8 py-16 sm:px-14">
                    <h2 class="max-w-4xl text-4xl font-black tracking-[-.04em] sm:text-6xl">{{ data_get($cta, 'title') }}</h2>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700">{{ data_get($cta, 'body') }}</p>
                    @if (data_get($cta, 'label'))
                        <a href="{{ data_get($cta, 'url', route('website.contact')) }}" class="mt-10 inline-flex rounded-full bg-slate-950 px-7 py-3 font-bold text-white">
                            {{ data_get($cta, 'label') }}
                        </a>
                    @endif
                </div>
            </section>
        @endif
    </main>

    <footer class="mx-auto flex max-w-7xl flex-col gap-4 border-t border-black/10 px-5 py-10 text-sm font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
        <p>© {{ now()->year }} PG Integrated.</p>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('client.login') }}">Client portal</a>
            <a href="{{ route('employee.login') }}">Employee login</a>
            <a href="{{ route('admin.cms.index') }}">CMS</a>
        </div>
    </footer>
</body>
</html>
