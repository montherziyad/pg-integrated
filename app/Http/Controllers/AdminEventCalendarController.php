<?php

namespace App\Http\Controllers;

use App\Services\EventCalendarService;
use Illuminate\View\View;

class AdminEventCalendarController extends Controller
{
    public function __construct(
        protected EventCalendarService $eventCalendarService
    ) {}

    public function index(): View
    {
        $events = $this->eventCalendarService->all();

        return view('admin.events.index', [
            'events' => $events,
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
}
