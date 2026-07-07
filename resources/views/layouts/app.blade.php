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
                @php
                    $canAccessScreen = fn (string $screen): bool => Auth::user()?->canAccessScreen($screen) ?? false;
                    $navGroups = [
                        'Website' => [
                            ['screen' => 'website_pages', 'route' => 'admin.cms.index', 'active' => 'admin.cms.*', 'label' => 'Website Pages'],
                            ['screen' => 'careers', 'route' => 'admin.careers.index', 'active' => ['admin.careers.*', 'admin.career-applications.*'], 'label' => 'Careers / Join Us'],
                            ['screen' => 'website_chat', 'route' => 'admin.website-chat.index', 'active' => 'admin.website-chat.*', 'label' => 'Website Chat'],
                        ],
                        'Clients' => [
                            ['screen' => 'clients', 'route' => 'admin.clients.index', 'active' => 'admin.clients.*', 'label' => 'Clients'],
                            ['screen' => 'projects', 'route' => 'admin.projects.index', 'active' => 'admin.projects.*', 'label' => 'Projects'],
                            ['screen' => 'client_requests', 'route' => 'admin.client-requests.index', 'active' => 'admin.client-requests.*', 'label' => 'Client Requests'],
                            ['screen' => 'crm', 'route' => 'crm.index', 'active' => 'crm.*', 'label' => 'CRM'],
                            ['screen' => 'support', 'route' => 'support.index', 'active' => 'support.*', 'label' => 'Customer Support'],
                        ],
                        'Growth' => [
                            ['screen' => 'ai_employee', 'route' => 'ai-employee.index', 'active' => 'ai-employee.*', 'label' => 'AI Employee'],
                            ['screen' => 'ai_workspace', 'route' => 'ai.workspace', 'active' => 'ai.*', 'label' => 'AI Workspace'],
                            ['screen' => 'marketing', 'route' => 'marketing.index', 'active' => 'marketing.*', 'label' => 'Marketing'],
                        ],
                        'Traffic' => [
                            ['screen' => 'email_intake', 'route' => 'email-intakes.index', 'active' => 'email-intakes.*', 'label' => 'Email Intake'],
                            ['screen' => 'jobs', 'route' => 'jobs.index', 'active' => 'jobs.*', 'label' => 'Jobs'],
                            ['screen' => 'deliveries', 'route' => 'deliveries.index', 'active' => 'deliveries.*', 'label' => 'Delivery / Handover'],
                            ['screen' => 'traffic_board', 'route' => 'traffic.index', 'active' => 'traffic.*', 'label' => 'Traffic Board'],
                            ['screen' => 'team_workload', 'route' => 'workload.index', 'active' => 'workload.*', 'label' => 'Team Workload'],
                            ['screen' => 'employee_leaves', 'route' => 'employee-leaves.index', 'active' => 'employee-leaves.*', 'label' => 'Employee Leaves'],
                            ['screen' => 'employee_handover', 'route' => 'handovers.index', 'active' => 'handovers.*', 'label' => 'Handover'],
                            ['screen' => 'archive', 'route' => 'archive.index', 'active' => 'archive.*', 'label' => 'Archive'],
                            ['screen' => 'reports', 'route' => 'reports.index', 'active' => 'reports.*', 'label' => 'Reports'],
                        ],
                        'System' => [
                            ['screen' => 'users', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Users'],
                            ['screen' => 'teams', 'route' => 'admin.teams.index', 'active' => 'admin.teams.*', 'label' => 'Teams'],
                            ['screen' => 'roles', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*', 'label' => 'Roles & Permissions'],
                            ['screen' => 'branches', 'route' => 'admin.branches.index', 'active' => 'admin.branches.*', 'label' => 'Branches'],
                            ['screen' => 'categories', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Categories'],
                            ['screen' => 'settings', 'route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'label' => 'Settings'],
                            ['screen' => 'events', 'route' => 'admin.events.index', 'active' => 'admin.events.*', 'label' => 'Events'],
                        ],
                    ];
                    $navLinkClass = fn ($active) => 'block px-4 py-3 rounded-xl '.(request()->routeIs(...(array) $active) ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white');
                @endphp

                <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Dashboard</a>

                @foreach($navGroups as $group => $items)
                    @php $visibleItems = collect($items)->filter(fn ($item) => $canAccessScreen($item['screen'])); @endphp

                    @if($visibleItems->isNotEmpty())
                        <div class="px-4 pt-5 pb-1 text-[11px] font-bold uppercase tracking-widest text-slate-500">{{ $group }}</div>

                        @foreach($visibleItems as $item)
                            <a href="{{ route($item['route']) }}" class="{{ $navLinkClass($item['active']) }}">{{ $item['label'] }}</a>
                        @endforeach
                    @endif
                @endforeach
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
                    @if(Auth::user()?->canAccessScreen('settings'))
                        <form method="POST" action="{{ route('admin.settings.clear-cache') }}" onsubmit="return confirm('Clear system cache now?');">
                            @csrf
                            <button class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                                Clear Cache
                            </button>
                        </form>
                    @endif

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
