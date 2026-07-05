<x-dynamic-component component="client-portal.layout" :client="$client" title="Calendar">
    <h1 class="text-4xl font-extrabold">Marketing calendar</h1>
    <p class="mt-2 text-slate-500">Upcoming national, seasonal, and campaign planning dates for {{ $client->country ?: 'Saudi Arabia' }}.</p>

    <section class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($calendarEvents as $event)
            <article class="rounded-3xl bg-white p-6">
                <div class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $event['date'] }} · {{ $event['type'] }}</div>
                <h2 class="mt-2 text-2xl font-extrabold">{{ $event['title'] }}</h2>
                <p class="mt-3 text-sm text-slate-500">{{ $event['country'] }}</p>
                <a href="{{ route('client.requests.create') }}" class="mt-5 inline-flex rounded-xl bg-slate-950 px-4 py-2 text-sm font-bold text-white">Plan a campaign</a>
            </article>
        @endforeach
    </section>
</x-dynamic-component>
