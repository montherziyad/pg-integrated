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

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Dashboard</a>

                <div class="px-4 pt-5 pb-1 text-[11px] font-bold uppercase tracking-widest text-slate-500">Website</div>
                <a href="{{ route('admin.cms.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.cms.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Website Pages</a>
                <a href="{{ route('admin.careers.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.careers.*') || request()->routeIs('admin.career-applications.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Careers / Join Us</a>
                <a href="{{ route('admin.website-chat.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.website-chat.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Website Chat</a>

                <div class="px-4 pt-5 pb-1 text-[11px] font-bold uppercase tracking-widest text-slate-500">Clients</div>
                <a href="{{ route('admin.clients.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.clients.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Clients</a>
                <a href="{{ route('admin.projects.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.projects.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Projects</a>
                <a href="{{ route('admin.client-requests.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.client-requests.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Client Requests</a>
                <a href="{{ route('crm.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('crm.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">CRM</a>
                <a href="{{ route('support.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('support.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Customer Support</a>

                <div class="px-4 pt-5 pb-1 text-[11px] font-bold uppercase tracking-widest text-slate-500">Growth</div>
                <a href="{{ route('ai-employee.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('ai-employee.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">AI Employee</a>
                <a href="{{ route('ai.workspace') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('ai.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">AI Workspace</a>
                <a href="{{ route('marketing.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('marketing.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Marketing</a>

                <div class="px-4 pt-5 pb-1 text-[11px] font-bold uppercase tracking-widest text-slate-500">Traffic</div>
                <a href="{{ route('email-intakes.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('email-intakes.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Email Intake</a>
                <a href="{{ route('jobs.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('jobs.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Jobs</a>
                <a href="{{ route('deliveries.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('deliveries.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Delivery / Handover</a>
                <a href="{{ route('traffic.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('traffic.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Traffic Board</a>
                <a href="{{ route('workload.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('workload.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Team Workload</a>
                <a href="{{ route('archive.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('archive.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Archive</a>
                <a href="{{ route('reports.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Reports</a>

                <div class="px-4 pt-5 pb-1 text-[11px] font-bold uppercase tracking-widest text-slate-500">System</div>
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Settings</a>
                <a href="{{ route('admin.events.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('admin.events.*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Events</a>
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

                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.settings.clear-cache') }}" onsubmit="return confirm('Clear system cache now?');">
                        @csrf
                        <button class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                            Clear Cache
                        </button>
                    </form>

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
