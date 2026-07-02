<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PG Integrated CreativeOps</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased bg-slate-100 text-slate-900">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-72 bg-slate-950 text-white flex flex-col">
            <div class="h-20 flex items-center px-6 border-b border-slate-800">
                <div>
                    <div class="text-xl font-bold">PG Integrated</div>
                    <div class="text-xs text-slate-400">CreativeOps Platform</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Dashboard</a>
                <a href="{{ route('email-intakes.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('email-intakes.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Email Intake</a>
                <a href="{{ route('jobs.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('jobs.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Jobs</a>
                <a href="{{ route('traffic.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('traffic.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Traffic Board</a>
                <a href="{{ route('workload.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('workload.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Team Workload</a>
                <a href="{{ route('admin.clients.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.clients.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Clients</a>
                <a href="{{ route('admin.projects.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.projects.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Projects</a>
                <a href="{{ route('archive.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('archive.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Archive</a>
                <a href="{{ route('reports.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Reports</a>
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Settings</a>
            </nav>

            <div class="p-4 border-t border-slate-800 text-xs text-slate-400">
                PG Integrated v1.0
            </div>
        </aside>

        <!-- Main Area -->
        <div class="flex-1 flex flex-col">

            <!-- Top Bar -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        {{ $header ?? 'Dashboard' }}
                    </h1>
                    <p class="text-sm text-slate-500">Studio operations command center</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
