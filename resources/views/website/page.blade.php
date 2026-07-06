@php
    $type = data_get($content, 'type', 'home');
    $hero = data_get($content, 'hero', []);
    $title = data_get($seo, 'title', $page?->title ?? data_get($hero, 'title', 'PG Integrated'));
    $description = data_get($seo, 'description', data_get($hero, 'body', 'PG Integrated creative, digital, production, and client operations.'));
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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>
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

        @media (min-width: 980px) {
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
                    <a href="{{ $navUrl($item) }}" class="hover:text-amber-600">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="pg-site-desktop-actions items-center gap-2">
                @auth
                    @if ($page?->exists)
                        <a href="{{ route('admin.cms.edit', $page) }}" class="rounded-full border border-amber-400 bg-amber-300 px-4 py-2 text-sm font-bold text-slate-950 hover:bg-amber-200">Edit this page</a>
                    @endif
                @endauth
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
                            <a href="{{ $navUrl($item) }}" class="rounded-2xl px-4 py-3 text-sm font-black uppercase tracking-[.14em] text-slate-700 hover:bg-slate-50 hover:text-amber-700">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-3 grid gap-2 border-t border-black/10 pt-3">
                        <a href="{{ route('client.login') }}" class="rounded-2xl border border-black/15 px-4 py-3 text-center text-sm font-black">Client Portal</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-center text-sm font-black text-white">Dashboard</a>
                            @if ($page?->exists)
                                <a href="{{ route('admin.cms.edit', $page) }}" class="rounded-2xl bg-amber-300 px-4 py-3 text-center text-sm font-black text-slate-950">Edit page</a>
                            @endif
                        @else
                            <a href="{{ route('employee.login') }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-center text-sm font-black text-white">Employee Login</a>
                        @endauth
                    </div>
                </div>
            </details>
        </div>
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
                    <h2 class="text-3xl font-black">Our regional offices</h2>
                    <p class="mt-3 leading-7 text-slate-400">PG Integrated locations across Saudi Arabia and regional markets.</p>

                    @php
                        $locations = collect(data_get($content, 'locations', []))->values();
                        $latitudes = $locations->pluck('lat')->filter(fn ($value) => is_numeric($value));
                        $longitudes = $locations->pluck('lng')->filter(fn ($value) => is_numeric($value));
                        $minLat = $latitudes->min() ?: 18;
                        $maxLat = $latitudes->max() ?: 34;
                        $minLng = $longitudes->min() ?: 30;
                        $maxLng = $longitudes->max() ?: 56;
                        $latRange = max(($maxLat - $minLat), 1);
                        $lngRange = max(($maxLng - $minLng), 1);
                    @endphp

                    <div class="relative mt-8 min-h-80 overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950 p-6">
                        <div class="absolute inset-0 opacity-25" style="background-image: radial-gradient(circle at 20% 30%, rgba(251,191,36,.7) 0 2px, transparent 3px), radial-gradient(circle at 65% 45%, rgba(255,255,255,.45) 0 1px, transparent 3px), linear-gradient(135deg, transparent 0 48%, rgba(255,255,255,.18) 49% 51%, transparent 52%); background-size: 72px 72px, 96px 96px, 120px 120px;"></div>
                        <div class="absolute left-6 top-6 rounded-full bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[.18em] text-amber-200">Branch Map</div>

                        @foreach ($locations as $location)
                            @php
                                $lat = is_numeric(data_get($location, 'lat')) ? (float) data_get($location, 'lat') : null;
                                $lng = is_numeric(data_get($location, 'lng')) ? (float) data_get($location, 'lng') : null;
                                $left = $lng !== null ? 10 + (($lng - $minLng) / $lngRange) * 80 : 50;
                                $top = $lat !== null ? 85 - (($lat - $minLat) / $latRange) * 70 : 50;
                            @endphp
                            <a href="{{ data_get($location, 'map_url') ?: 'https://www.google.com/maps/search/?api=1&query='.urlencode(data_get($location, 'city').' '.data_get($location, 'address')) }}" target="_blank" rel="noopener" class="group absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ round($left, 2) }}%; top: {{ round($top, 2) }}%;">
                                <span class="absolute left-1/2 top-1/2 h-8 w-8 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-300/25 group-hover:bg-amber-300/40"></span>
                                <span class="relative flex h-5 w-5 items-center justify-center rounded-full bg-amber-300 ring-4 ring-white/20"></span>
                                <span class="absolute left-1/2 top-7 hidden -translate-x-1/2 whitespace-nowrap rounded-xl bg-white px-3 py-2 text-xs font-black text-slate-950 shadow-xl group-hover:block">{{ data_get($location, 'city') }}</span>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8 grid gap-5">
                        @foreach ($locations as $location)
                            <article class="rounded-2xl border border-white/10 p-5">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h3 class="text-2xl font-black">{{ data_get($location, 'city') }}</h3>
                                        <p class="mt-2 text-slate-300">{{ data_get($location, 'address') }}</p>
                                    </div>
                                    <a href="{{ data_get($location, 'map_url') ?: 'https://www.google.com/maps/search/?api=1&query='.urlencode(data_get($location, 'city').' '.data_get($location, 'address')) }}" target="_blank" rel="noopener" class="shrink-0 rounded-full border border-white/15 px-4 py-2 text-sm font-bold text-amber-200 hover:bg-white/10">Open map</a>
                                </div>
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
    <div id="pg-chat" class="fixed bottom-5 right-5 z-[70]">
        <button id="pg-chat-toggle" class="rounded-full bg-slate-950 px-5 py-3 font-bold text-white shadow-2xl">Chat with PG</button>
        <div id="pg-chat-panel" class="hidden mt-3 w-[min(92vw,390px)] overflow-hidden rounded-3xl border border-black/10 bg-white shadow-2xl">
            <div class="bg-slate-950 p-5 text-white"><div class="font-black">PG Integrated Assistant</div><div class="mt-1 text-xs text-slate-300">Public information only · Human support available</div></div>
            <div id="pg-chat-messages" class="h-72 space-y-3 overflow-y-auto bg-slate-50 p-4 text-sm"><div class="rounded-2xl bg-white p-3">Hello! Ask me about PG Integrated services, work, or how to start a project.</div></div>
            <form id="pg-chat-form" class="flex gap-2 border-t p-3"><input id="pg-chat-input" maxlength="2000" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm" placeholder="Type your question..." required><button class="rounded-xl bg-slate-950 px-4 text-sm font-bold text-white">Send</button></form>
            <button id="pg-human-toggle" class="w-full border-t px-4 py-3 text-sm font-bold text-amber-700">Talk to a team member / Schedule a call</button>
            <form id="pg-contact-form" class="hidden space-y-3 border-t p-4">
                <input name="name" class="w-full rounded-xl border-slate-300 text-sm" placeholder="Name" required>
                <div class="grid grid-cols-2 gap-2"><input name="email" type="email" class="w-full rounded-xl border-slate-300 text-sm" placeholder="Email"><input name="phone" class="w-full rounded-xl border-slate-300 text-sm" placeholder="Phone"></div>
                <input name="company" class="w-full rounded-xl border-slate-300 text-sm" placeholder="Company">
                <input name="preferred_at" type="datetime-local" class="w-full rounded-xl border-slate-300 text-sm">
                <textarea name="contact_notes" rows="2" class="w-full rounded-xl border-slate-300 text-sm" placeholder="How can we help?"></textarea>
                <button class="w-full rounded-xl bg-amber-300 px-4 py-3 text-sm font-black">Request contact</button>
            </form>
        </div>
    </div>
    <script>
    (() => {
        const panel=document.getElementById('pg-chat-panel'), messages=document.getElementById('pg-chat-messages');
        const tokenKey='pg_website_chat_token', csrf=document.querySelector('meta[name="csrf-token"]').content;
        const add=(text,user=false)=>{const el=document.createElement('div');el.className='rounded-2xl p-3 '+(user?'ml-8 bg-amber-200':'mr-8 bg-white');el.textContent=text;messages.appendChild(el);messages.scrollTop=messages.scrollHeight;};
        document.getElementById('pg-chat-toggle').onclick=()=>panel.classList.toggle('hidden');
        document.getElementById('pg-human-toggle').onclick=()=>document.getElementById('pg-contact-form').classList.toggle('hidden');
        document.getElementById('pg-chat-form').onsubmit=async(e)=>{e.preventDefault();const input=document.getElementById('pg-chat-input'),text=input.value.trim();if(!text)return;add(text,true);input.value='';
            try{const r=await fetch(@json(route('website-assistant.message')),{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({visitor_token:localStorage.getItem(tokenKey),message:text,locale:document.documentElement.lang})});const d=await r.json();if(!r.ok)throw new Error(d.message||'Request failed');localStorage.setItem(tokenKey,d.visitor_token);add(d.answer);if(d.needs_human)add('I can also record your details for a team member—use the contact button below.');}catch{add('The assistant is temporarily unavailable. Please request contact with our team.');}};
        document.getElementById('pg-contact-form').onsubmit=async(e)=>{e.preventDefault();const form=e.currentTarget,data=Object.fromEntries(new FormData(form));data.visitor_token=localStorage.getItem(tokenKey);data.locale=document.documentElement.lang;
            try{const r=await fetch(@json(route('website-assistant.contact')),{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify(data)});const d=await r.json();if(!r.ok)throw new Error(d.message||'Request failed');localStorage.setItem(tokenKey,d.visitor_token);add(d.message);form.reset();form.classList.add('hidden');}catch(err){add(err.message);}};
    })();
    </script>
</body>
</html>
