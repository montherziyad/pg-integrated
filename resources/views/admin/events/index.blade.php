<x-app-layout>
    <x-slot name="header">Events</x-slot>

    <div class="space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Events command calendar</h2>
                <p class="pg-subtitle mt-1">One calendar connected to the client portal, employee dashboards, traffic, marketing, and management roles.</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-800">
                Role-aware · Client + Employee visibility
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-5">
            <div class="rounded-3xl border border-slate-200 bg-white p-5"><div class="text-sm font-bold uppercase tracking-wide text-slate-500">All events</div><div class="mt-3 text-4xl font-extrabold">{{ $events->count() }}</div></div>
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-emerald-700">Upcoming</div><div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $upcomingEvents->count() }}</div></div>
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-amber-700">Client portal</div><div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $clientEvents->count() }}</div></div>
            <div class="rounded-3xl border border-blue-200 bg-blue-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-blue-700">Employee ops</div><div class="mt-3 text-4xl font-extrabold text-blue-950">{{ $employeeEvents->count() }}</div></div>
            <div class="rounded-3xl border border-purple-200 bg-purple-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-purple-700">Traffic / marketing</div><div class="mt-3 text-4xl font-extrabold text-purple-950">{{ $trafficEvents->count() + $marketingEvents->count() }}</div></div>
        </div>

        <section class="pg-card">
            <div class="pg-card-body">
                <h3 class="text-xl font-bold">Upcoming campaign windows</h3>
                <p class="mt-1 text-sm text-slate-500">These appear in dashboards according to audience and role.</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @forelse($upcomingEvents as $event)
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['type'] }}</div>
                            <h4 class="mt-3 text-lg font-black">{{ $event['title_ar'] }}</h4>
                            <p class="mt-1 font-semibold text-slate-600">{{ $event['title'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-500">{{ $event['note'] }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($event['audience'] as $audience)
                                    <span class="rounded-full bg-white px-3 py-1 text-[11px] font-black uppercase text-slate-500">{{ $audience }}</span>
                                @endforeach
                            </div>
                            <div class="mt-4 rounded-full bg-white px-3 py-2 text-xs font-bold text-slate-600">
                                @if($event['days_until'] === 0) Today @elseif($event['days_until'] > 0) In {{ $event['days_until'] }} days @else Passed @endif
                            </div>
                        </article>
                    @empty
                        <div class="text-slate-500">No upcoming events.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="pg-card"><div class="pg-card-body">
                <h3 class="text-xl font-bold">Client portal visibility</h3>
                <p class="mt-1 text-sm text-slate-500">Events shown to clients in their dashboard and calendar.</p>
                <div class="mt-5 space-y-3">
                    @foreach($clientEvents->take(6) as $event)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['country'] }}</div>
                            <div class="mt-1 font-black">{{ $event['title'] }}</div>
                            <div class="mt-1 text-sm text-slate-500">{{ $event['action'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div></div>

            <div class="pg-card"><div class="pg-card-body">
                <h3 class="text-xl font-bold">Employee / role operations</h3>
                <p class="mt-1 text-sm text-slate-500">Events used by internal teams depending on their role.</p>
                <div class="mt-5 space-y-3">
                    @foreach($employeeEvents->take(6) as $event)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['type'] }}</div>
                            <div class="mt-1 font-black">{{ $event['title'] }}</div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($event['roles'] as $role)
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-black uppercase text-slate-600">{{ $role }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div></div>
        </section>

        <section class="pg-card">
            <div class="pg-card-body">
                <h3 class="mb-5 text-xl font-bold">All events</h3>
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="w-full bg-white text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-500">
                            <tr><th class="px-4 py-3">Date</th><th class="px-4 py-3">Event</th><th class="px-4 py-3">Type</th><th class="px-4 py-3">Audience</th><th class="px-4 py-3">Roles</th><th class="px-4 py-3">Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr class="border-t border-slate-100">
                                    <td class="px-4 py-4 font-bold">{{ $event['formatted_date'] }}</td>
                                    <td class="px-4 py-4"><div class="font-semibold">{{ $event['title'] }}</div><div class="mt-1 text-slate-500">{{ $event['title_ar'] }}</div></td>
                                    <td class="px-4 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase text-slate-600">{{ $event['type'] }}</span></td>
                                    <td class="px-4 py-4 text-slate-500">{{ implode(', ', $event['audience']) }}</td>
                                    <td class="px-4 py-4 text-slate-500">{{ implode(', ', $event['roles']) }}</td>
                                    <td class="px-4 py-4 text-slate-500">{{ $event['action'] ?: $event['note'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
