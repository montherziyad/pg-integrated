<x-app-layout>
    <x-slot name="header">Events</x-slot>

    <div class="space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Events & campaign calendar</h2>
                <p class="pg-subtitle mt-1">Saudi market calendar for campaign planning, traffic readiness, and client content windows.</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-800">
                Saudi Arabia · Local planning calendar
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5">
                <div class="text-sm font-bold uppercase tracking-wide text-slate-500">All events</div>
                <div class="mt-3 text-4xl font-extrabold">{{ $events->count() }}</div>
            </div>
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                <div class="text-sm font-bold uppercase tracking-wide text-emerald-700">Upcoming</div>
                <div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $upcomingEvents->count() }}</div>
            </div>
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5">
                <div class="text-sm font-bold uppercase tracking-wide text-amber-700">National moments</div>
                <div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $nationalEvents->count() }}</div>
            </div>
        </div>

        <section class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">Upcoming campaign windows</h3>
                        <p class="mt-1 text-sm text-slate-500">Use this view to prepare briefs, client reminders, content plans, and production schedules.</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @forelse($upcomingEvents as $event)
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['type'] }}</div>
                            <h4 class="mt-3 text-lg font-black">{{ $event['title_ar'] }}</h4>
                            <p class="mt-1 font-semibold text-slate-600">{{ $event['title'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-500">{{ $event['note'] }}</p>
                            <div class="mt-4 rounded-full bg-white px-3 py-2 text-xs font-bold text-slate-600">
                                @if($event['days_until'] === 0)
                                    Today
                                @elseif($event['days_until'] > 0)
                                    In {{ $event['days_until'] }} days
                                @else
                                    Passed
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="text-slate-500">No upcoming events.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="pg-card">
            <div class="pg-card-body">
                <h3 class="mb-5 text-xl font-bold">All events</h3>
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="w-full bg-white text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Event</th>
                                <th class="px-4 py-3">Arabic</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Planning note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr class="border-t border-slate-100">
                                    <td class="px-4 py-4 font-bold">{{ $event['formatted_date'] }}</td>
                                    <td class="px-4 py-4 font-semibold">{{ $event['title'] }}</td>
                                    <td class="px-4 py-4 font-semibold">{{ $event['title_ar'] }}</td>
                                    <td class="px-4 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase text-slate-600">{{ $event['type'] }}</span></td>
                                    <td class="px-4 py-4 text-slate-500">{{ $event['note'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">Events</x-slot>

    <div class="space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Events & campaign calendar</h2>
                <p class="pg-subtitle mt-1">Saudi market calendar for campaign planning, traffic readiness, and client content windows.</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-800">
                Saudi Arabia · Local planning calendar
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5">
                <div class="text-sm font-bold uppercase tracking-wide text-slate-500">All events</div>
                <div class="mt-3 text-4xl font-extrabold">{{ $events->count() }}</div>
            </div>
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                <div class="text-sm font-bold uppercase tracking-wide text-emerald-700">Upcoming</div>
                <div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $upcomingEvents->count() }}</div>
            </div>
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5">
                <div class="text-sm font-bold uppercase tracking-wide text-amber-700">National moments</div>
                <div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $nationalEvents->count() }}</div>
            </div>
        </div>

        <section class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">Upcoming campaign windows</h3>
                        <p class="mt-1 text-sm text-slate-500">Use this view to prepare briefs, client reminders, content plans, and production schedules.</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @forelse($upcomingEvents as $event)
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['type'] }}</div>
                            <h4 class="mt-3 text-lg font-black">{{ $event['title_ar'] }}</h4>
                            <p class="mt-1 font-semibold text-slate-600">{{ $event['title'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-500">{{ $event['note'] }}</p>
                            <div class="mt-4 rounded-full bg-white px-3 py-2 text-xs font-bold text-slate-600">
                                @if($event['days_until'] === 0)
                                    Today
                                @elseif($event['days_until'] > 0)
                                    In {{ $event['days_until'] }} days
                                @else
                                    Passed
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="text-slate-500">No upcoming events.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="pg-card">
            <div class="pg-card-body">
                <h3 class="mb-5 text-xl font-bold">All events</h3>
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="w-full bg-white text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Event</th>
                                <th class="px-4 py-3">Arabic</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Planning note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr class="border-t border-slate-100">
                                    <td class="px-4 py-4 font-bold">{{ $event['formatted_date'] }}</td>
                                    <td class="px-4 py-4 font-semibold">{{ $event['title'] }}</td>
                                    <td class="px-4 py-4 font-semibold">{{ $event['title_ar'] }}</td>
                                    <td class="px-4 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase text-slate-600">{{ $event['type'] }}</span></td>
                                    <td class="px-4 py-4 text-slate-500">{{ $event['note'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
