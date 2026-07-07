<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminEventCalendarController extends Controller
{
    public function index(): View
    {
        $events = collect($this->events())
            ->sortBy('date')
            ->values()
            ->map(function (array $event) {
                $date = Carbon::parse($event['date']);

                return array_merge($event, [
                    'formatted_date' => $date->format('d M Y'),
                    'is_upcoming' => $date->isToday() || $date->isFuture(),
                    'days_until' => now()->startOfDay()->diffInDays($date->startOfDay(), false),
                ]);
            });

        return view('admin.events.index', [
            'events' => $events,
            'upcomingEvents' => $events->where('is_upcoming', true)->take(8)->values(),
            'nationalEvents' => $events->where('type', 'national')->values(),
            'seasonalEvents' => $events->where('type', 'seasonal')->values(),
            'planningEvents' => $events->where('type', 'planning')->values(),
        ]);
    }

    private function events(): array
    {
        return [
            ['date' => '2026-01-01', 'title' => 'New Year Planning Window', 'title_ar' => 'نافذة تخطيط بداية العام', 'type' => 'planning', 'country' => 'Saudi Arabia', 'note' => 'Annual campaign planning, retainers, and content roadmap preparation.'],
            ['date' => '2026-02-18', 'title' => 'Ramadan Campaign Season', 'title_ar' => 'موسم حملات رمضان', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'note' => 'Prepare Ramadan content, offers, production windows, and approval deadlines.'],
            ['date' => '2026-02-22', 'title' => 'Saudi Founding Day', 'title_ar' => 'يوم التأسيس السعودي', 'type' => 'national', 'country' => 'Saudi Arabia', 'note' => 'National creative, retail, social, and activation planning window.'],
            ['date' => '2026-03-20', 'title' => 'Eid Al-Fitr Content Window', 'title_ar' => 'نافذة محتوى عيد الفطر', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'note' => 'Eid greetings, offers, production delivery, and social publishing window.'],
            ['date' => '2026-05-26', 'title' => 'Hajj Campaign Readiness', 'title_ar' => 'جاهزية حملات الحج', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'note' => 'Hajj-related logistics, messages, content moderation, and delivery planning.'],
            ['date' => '2026-05-27', 'title' => 'Eid Al-Adha Content Window', 'title_ar' => 'نافذة محتوى عيد الأضحى', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'note' => 'Eid Al-Adha greetings, retail campaigns, client approvals, and publishing.'],
            ['date' => '2026-09-23', 'title' => 'Saudi National Day', 'title_ar' => 'اليوم الوطني السعودي', 'type' => 'national', 'country' => 'Saudi Arabia', 'note' => 'Major Saudi campaign moment for brand films, outdoor, digital, activations, and retail.'],
            ['date' => '2026-11-20', 'title' => 'End of Year Campaign Planning', 'title_ar' => 'تخطيط حملات نهاية العام', 'type' => 'planning', 'country' => 'Saudi Arabia', 'note' => 'Plan Q1, annual retainers, reporting, archive cleanup, and renewal proposals.'],
        ];
    }
}
