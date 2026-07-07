<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Team;
use App\Services\EventCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminEventCalendarController extends Controller
{
    public function __construct(
        protected EventCalendarService $eventCalendarService
    ) {}

    public function index(Request $request): View
    {
        $this->eventCalendarService->ensureDefaultEventsExist();

        $events = $this->eventCalendarService->all();
        $allEvents = CalendarEvent::query()->orderBy('event_date')->get();
        $editingEvent = $request->filled('edit')
            ? CalendarEvent::query()->find($request->integer('edit'))
            : null;

        return view('admin.events.index', [
            'events' => $events,
            'allEvents' => $allEvents,
            'editingEvent' => $editingEvent,
            'teams' => Team::query()->where('is_active', true)->orderBy('name')->get(),
            'audienceOptions' => $this->audienceOptions(),
            'roleOptions' => $this->roleOptions(),
            'typeOptions' => $this->typeOptions(),
            'upcomingEvents' => $this->eventCalendarService->upcoming(null, 'Saudi Arabia', 8),
            'clientEvents' => $events->filter(fn (array $event) => in_array('client', $event['audience'] ?? [], true))->values(),
            'employeeEvents' => $events->filter(fn (array $event) => in_array('employee', $event['audience'] ?? [], true))->values(),
            'trafficEvents' => $events->filter(fn (array $event) => in_array('traffic', $event['audience'] ?? [], true))->values(),
            'marketingEvents' => $events->filter(fn (array $event) => in_array('marketing', $event['audience'] ?? [], true))->values(),
            'nationalEvents' => $events->where('type', 'national')->values(),
            'seasonalEvents' => $events->where('type', 'seasonal')->values(),
            'planningEvents' => $events->where('type', 'planning')->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        CalendarEvent::query()->create($this->validatedData($request));

        return redirect()->route('admin.events.index')->with('status', 'Event created.');
    }

    public function update(Request $request, CalendarEvent $event): RedirectResponse
    {
        $event->update($this->validatedData($request));

        return redirect()->route('admin.events.index')->with('status', 'Event updated.');
    }

    public function toggle(CalendarEvent $event): RedirectResponse
    {
        $event->update(['is_active' => ! $event->is_active]);

        return back()->with('status', $event->is_active ? 'Event activated.' : 'Event paused.');
    }

    public function destroy(CalendarEvent $event): RedirectResponse
    {
        $event->delete();

        return back()->with('status', 'Event deleted.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'event_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys($this->typeOptions()))],
            'country' => ['required', 'string', 'max:255'],
            'audience' => ['nullable', 'array'],
            'audience.*' => [Rule::in(array_keys($this->audienceOptions()))],
            'roles' => ['nullable', 'array'],
            'roles.*' => [Rule::in(array_keys($this->roleOptions()))],
            'team_ids' => ['nullable', 'array'],
            'team_ids.*' => ['integer', 'exists:teams,id'],
            'note' => ['nullable', 'string', 'max:2000'],
            'action' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['audience'] = array_values($data['audience'] ?? []);
        $data['roles'] = array_values($data['roles'] ?? []);
        $data['team_ids'] = array_values(array_map('intval', $data['team_ids'] ?? []));
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function audienceOptions(): array
    {
        return [
            'client' => 'Client portal',
            'employee' => 'Employee dashboard',
            'traffic' => 'Traffic board',
            'marketing' => 'Marketing',
            'client_service' => 'Client service',
            'management' => 'Management',
        ];
    }

    private function roleOptions(): array
    {
        return [
            'Account Manager' => 'Account Manager',
            'Client Service' => 'Client Service',
            'Operations Manager' => 'Operations Manager',
            'Traffic Manager' => 'Traffic Manager',
            'Creative' => 'Creative',
            'Content' => 'Content',
            'Motion' => 'Motion',
            'Marketing' => 'Marketing',
            'Media Zone' => 'Media Zone',
            'Archive Officer' => 'Archive Officer',
        ];
    }

    private function typeOptions(): array
    {
        return [
            'planning' => 'Planning',
            'seasonal' => 'Seasonal',
            'national' => 'National',
            'traffic' => 'Traffic',
            'operations' => 'Operations',
            'management' => 'Management',
        ];
    }
}
