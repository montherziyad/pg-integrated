<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Client Portal' }} | PG Integrated</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-950 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-6 py-5 lg:px-10">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('client.portal') }}" class="text-lg font-extrabold">PG Integrated</a>
                    <div class="text-xs text-slate-500">Client Portal</div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="hidden text-right sm:block">
                        <div class="text-sm font-bold">{{ $client->name }}</div>
                        <div class="text-xs text-slate-500">{{ $client->email }}</div>
                    </div>
                    @if ($client->avatar_path)
                        <img src="{{ $client->avatar_path }}" alt="{{ $client->name }}" class="h-11 w-11 rounded-full object-cover">
                    @endif
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold">Sign out</button>
                    </form>
                </div>
            </div>
            <nav class="flex gap-2 overflow-x-auto text-sm font-semibold">
                @foreach ([
                    ['label' => 'Dashboard', 'route' => 'client.portal'],
                    ['label' => 'Profile', 'route' => 'client.profile'],
                    ['label' => 'Projects', 'route' => 'client.projects'],
                    ['label' => 'New Request', 'route' => 'client.requests.create'],
                    ['label' => 'Calendar', 'route' => 'client.calendar'],
                ] as $item)
                    <a href="{{ route($item['route']) }}" class="shrink-0 rounded-full px-4 py-2 {{ request()->routeIs($item['route']) ? 'bg-slate-950 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10 lg:px-10">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
        @endif
        {{ $slot }}
    </main>
</body>
</html>
