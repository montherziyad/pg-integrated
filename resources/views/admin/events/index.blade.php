<x-app-layout>
    <x-slot name="header">Events</x-slot>

    <div class="space-y-8">
        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 font-bold text-emerald-800">{{ session('status') }}</div>
        @endif

        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="pg-title">Events command calendar</h2>
                <p class="pg-subtitle mt-1">Create, edit, activate, pause, and route events to client portal, employee dashboards, teams, and roles.</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-800">
                Editable · Active/Paused · Team visibility
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-5">
            <div class="rounded-3xl border border-slate-200 bg-white p-5"><div class="text-sm font-bold uppercase tracking-wide text-slate-500">All events</div><div class="mt-3 text-4xl font-extrabold">{{ $allEvents->count() }}</div></div>
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-emerald-700">Active upcoming</div><div class="mt-3 text-4xl font-extrabold text-emerald-950">{{ $upcomingEvents->count() }}</div></div>
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-amber-700">Client portal</div><div class="mt-3 text-4xl font-extrabold text-amber-950">{{ $clientEvents->count() }}</div></div>
            <div class="rounded-3xl border border-blue-200 bg-blue-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-blue-700">Employee ops</div><div class="mt-3 text-4xl font-extrabold text-blue-950">{{ $employeeEvents->count() }}</div></div>
            <div class="rounded-3xl border border-purple-200 bg-purple-50 p-5"><div class="text-sm font-bold uppercase tracking-wide text-purple-700">Traffic / marketing</div><div class="mt-3 text-4xl font-extrabold text-purple-950">{{ $trafficEvents->count() + $marketingEvents->count() }}</div></div>
        </div>

        <section class="pg-card">
            <div class="pg-card-body">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold">{{ $editingEvent ? 'Edit event' : 'Add new event' }}</h3>
                        <p class="mt-1 text-sm text-slate-500">If no team is selected, the event is visible to all teams allowed by the audience/role.</p>
                    </div>
                    @if($editingEvent)
                        <a href="{{ route('admin.events.index') }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50">Cancel edit</a>
                    @endif
                </div>

                <form method="POST" action="{{ $editingEvent ? route('admin.events.update', $editingEvent) : route('admin.events.store') }}" class="space-y-5">
                    @csrf
                    @if($editingEvent) @method('PUT') @endif

                    <div class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label class="text-sm font-bold text-slate-700">Date</label>
                            <input type="date" name="event_date" value="{{ old('event_date', optional($editingEvent?->event_date)->toDateString()) }}" class="mt-1 w-full rounded-xl border-slate-300" required>
                            @error('event_date') <div class="mt-1 text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Title</label>
                            <input name="title" value="{{ old('title', $editingEvent?->title) }}" class="mt-1 w-full rounded-xl border-slate-300" required>
                            @error('title') <div class="mt-1 text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700">Type</label>
                            <select name="type" class="mt-1 w-full rounded-xl border-slate-300">
                                @foreach($typeOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type', $editingEvent?->type ?? 'planning') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-bold text-slate-700">Arabic title</label>
                            <input name="title_ar" value="{{ old('title_ar', $editingEvent?->title_ar) }}" class="mt-1 w-full rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700">Country</label>
                            <input name="country" value="{{ old('country', $editingEvent?->country ?? 'Saudi Arabia') }}" class="mt-1 w-full rounded-xl border-slate-300" required>
                        </div>
                    </div>

                    <div class="grid gap-4 xl:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-3 font-bold">Show in</div>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach($audienceOptions as $value => $label)
                                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                                        <input type="checkbox" name="audience[]" value="{{ $value }}" @checked(in_array($value, old('audience', $editingEvent?->audience ?? []), true))>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-3 font-bold">Roles</div>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach($roleOptions as $value => $label)
                                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                                        <input type="checkbox" name="roles[]" value="{{ $value }}" @checked(in_array($value, old('roles', $editingEvent?->roles ?? []), true))>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-3 font-bold">Teams</div>
                            <p class="mb-3 text-xs text-slate-500">Leave empty to show to all teams.</p>
                            <div class="max-h-44 space-y-2 overflow-auto pr-2">
                                @foreach($teams as $team)
                                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                                        <input type="checkbox" name="team_ids[]" value="{{ $team->id }}" @checked(in_array($team->id, array_map('intval', old('team_ids', $editingEvent?->team_ids ?? [])), true))>
                                        {{ $team->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-bold text-slate-700">Planning note</label>
                            <textarea name="note" rows="4" class="mt-1 w-full rounded-xl border-slate-300">{{ old('note', $editingEvent?->note) }}</textarea>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700">Action required</label>
                            <textarea name="action" rows="4" class="mt-1 w-full rounded-xl border-slate-300">{{ old('action', $editingEvent?->action) }}</textarea>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 font-bold text-slate-700">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editingEvent?->is_active ?? true))>
                        Active / visible
                    </label>

                    <button class="rounded-xl bg-slate-950 px-5 py-3 font-bold text-white hover:bg-slate-800">{{ $editingEvent ? 'Update event' : 'Create event' }}</button>
                </form>
            </div>
        </section>

        <section class="pg-card">
            <div class="pg-card-body">
                <h3 class="mb-5 text-xl font-bold">Manage events</h3>
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="w-full bg-white text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-500">
                            <tr><th class="px-4 py-3">Date</th><th class="px-4 py-3">Event</th><th class="px-4 py-3">Visibility</th><th class="px-4 py-3">Teams</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Actions</th></tr>
                        </thead>
                        <tbody>
                            @foreach($allEvents as $event)
                                <tr class="border-t border-slate-100 align-top">
                                    <td class="px-4 py-4 font-bold">{{ $event->event_date?->format('d M Y') }}</td>
                                    <td class="px-4 py-4"><div class="font-semibold">{{ $event->title }}</div><div class="mt-1 text-slate-500">{{ $event->title_ar }}</div><div class="mt-2"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase text-slate-600">{{ $event->type }}</span></div></td>
                                    <td class="px-4 py-4 text-slate-500">{{ implode(', ', $event->audience ?: []) ?: '—' }}</td>
                                    <td class="px-4 py-4 text-slate-500">
                                        @php $eventTeamIds = array_map('intval', $event->team_ids ?: []); @endphp
                                        @if(empty($eventTeamIds))
                                            All teams
                                        @else
                                            {{ $teams->whereIn('id', $eventTeamIds)->pluck('name')->implode(', ') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-4"><span class="rounded-full px-3 py-1 text-xs font-black {{ $event->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $event->is_active ? 'Active' : 'Paused' }}</span></td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.events.index', ['edit' => $event->id]) }}" class="rounded-full border border-slate-300 px-3 py-1 text-xs font-bold text-slate-700 hover:bg-slate-50">Edit</a>
                                            <form method="POST" action="{{ route('admin.events.toggle', $event) }}">@csrf @method('PATCH')<button class="rounded-full border border-amber-300 px-3 py-1 text-xs font-bold text-amber-700 hover:bg-amber-50">{{ $event->is_active ? 'Pause' : 'Activate' }}</button></form>
                                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event?')">@csrf @method('DELETE')<button class="rounded-full border border-red-300 px-3 py-1 text-xs font-bold text-red-700 hover:bg-red-50">Delete</button></form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="pg-card"><div class="pg-card-body"><h3 class="text-xl font-bold">Client portal visibility</h3><p class="mt-1 text-sm text-slate-500">Active events shown to clients in dashboard and calendar.</p><div class="mt-5 space-y-3">@foreach($clientEvents->take(6) as $event)<div class="rounded-2xl border border-slate-200 bg-white p-4"><div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['country'] }}</div><div class="mt-1 font-black">{{ $event['title'] }}</div><div class="mt-1 text-sm text-slate-500">{{ $event['action'] }}</div></div>@endforeach</div></div></div>
            <div class="pg-card"><div class="pg-card-body"><h3 class="text-xl font-bold">Employee / role operations</h3><p class="mt-1 text-sm text-slate-500">Active events shown internally according to audience, role, and selected teams.</p><div class="mt-5 space-y-3">@foreach($employeeEvents->take(6) as $event)<div class="rounded-2xl border border-slate-200 bg-white p-4"><div class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $event['formatted_date'] }} · {{ $event['type'] }}</div><div class="mt-1 font-black">{{ $event['title'] }}</div><div class="mt-2 flex flex-wrap gap-2">@foreach($event['roles'] as $role)<span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-black uppercase text-slate-600">{{ $role }}</span>@endforeach</div></div>@endforeach</div></div></div>
        </section>
    </div>
</x-app-layout>
