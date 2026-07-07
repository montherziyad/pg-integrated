<?php

namespace App\Services;

use App\Models\CalendarEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class EventCalendarService
{
    public function all(?string $audience = null, ?string $country = null, ?int $teamId = null): Collection
    {
        $this->ensureDefaultEventsExist();

        return $this->sourceEvents()
            ->filter(fn (array $event) => $this->matchesAudience($event, $audience))
            ->filter(fn (array $event) => $this->matchesCountry($event, $country))
            ->filter(fn (array $event) => $this->matchesTeam($event, $teamId))
            ->sortBy('date')
            ->values()
            ->map(fn (array $event) => $this->decorate($event));
    }

    public function upcoming(?string $audience = null, ?string $country = null, int $limit = 8, ?int $teamId = null): Collection
    {
        return $this->all($audience, $country, $teamId)
            ->filter(fn (array $event) => $event['is_upcoming'])
            ->take($limit)
            ->values();
    }

    public function defaultEvents(): array
    {
        return $this->events();
    }

    public function ensureDefaultEventsExist(): void
    {
        if (! Schema::hasTable('calendar_events') || CalendarEvent::query()->exists()) {
            return;
        }

        foreach ($this->defaultEvents() as $event) {
            CalendarEvent::query()->create([
                'event_date' => $event['date'],
                'title' => $event['title'],
                'title_ar' => $event['title_ar'] ?? null,
                'type' => $event['type'] ?? 'planning',
                'country' => $event['country'] ?? 'Saudi Arabia',
                'audience' => $event['audience'] ?? [],
                'roles' => $event['roles'] ?? [],
                'team_ids' => [],
                'note' => $event['note'] ?? null,
                'action' => $event['action'] ?? null,
                'is_active' => true,
            ]);
        }
    }

    private function sourceEvents(): Collection
    {
        if (! Schema::hasTable('calendar_events')) {
            return collect($this->events());
        }

        return CalendarEvent::query()
            ->where('is_active', true)
            ->orderBy('event_date')
            ->get()
            ->map(fn (CalendarEvent $event) => [
                'id' => $event->id,
                'date' => $event->event_date?->toDateString(),
                'title' => $event->title,
                'title_ar' => $event->title_ar,
                'type' => $event->type,
                'country' => $event->country,
                'audience' => $event->audience ?: [],
                'roles' => $event->roles ?: [],
                'team_ids' => $event->team_ids ?: [],
                'note' => $event->note,
                'action' => $event->action,
            ]);
    }

    private function decorate(array $event): array
    {
        $date = Carbon::parse($event['date']);
        $eventDay = Carbon::parse($event['date'])->startOfDay();

        return array_merge([
            'title_ar' => null,
            'note' => null,
            'action' => null,
            'audience' => [],
            'roles' => [],
        ], $event, [
            'formatted_date' => $date->format('d M Y'),
            'is_upcoming' => $eventDay->isToday() || $eventDay->isFuture(),
            'days_until' => now()->startOfDay()->diffInDays($eventDay, false),
        ]);
    }

    private function matchesAudience(array $event, ?string $audience): bool
    {
        if ($audience === null) {
            return true;
        }

        $audiences = $event['audience'] ?? [];

        return in_array('all', $audiences, true) || in_array($audience, $audiences, true);
    }

    private function matchesTeam(array $event, ?int $teamId): bool
    {
        if ($teamId === null) {
            return true;
        }

        $teamIds = array_filter(array_map('intval', $event['team_ids'] ?? []));

        return $teamIds === [] || in_array($teamId, $teamIds, true);
    }

    private function matchesCountry(array $event, ?string $country): bool
    {
        if (! $country) {
            return true;
        }

        if (($event['country'] ?? null) === 'Global') {
            return true;
        }

        return str($country)->lower()->contains(['saudi', 'ksa', 'arabia'])
            ? ($event['country'] ?? null) === 'Saudi Arabia'
            : in_array(($event['country'] ?? null), ['Global', $country], true);
    }

    private function events(): array
    {
        return [
            ['date' => '2026-01-01', 'title' => 'New Year Planning Window', 'title_ar' => 'نافذة تخطيط بداية العام', 'type' => 'planning', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing', 'management'], 'roles' => ['Account Manager', 'Traffic Manager', 'Marketing'], 'note' => 'Annual campaign planning, retainers, and content roadmap preparation.', 'action' => 'Prepare annual calendars, retainers, and client renewal opportunities.'],
            ['date' => '2026-01-15', 'title' => 'Client Q1 Retainer Review', 'title_ar' => 'مراجعة عقود الربع الأول', 'type' => 'planning', 'country' => 'Saudi Arabia', 'audience' => ['employee', 'management', 'client_service'], 'roles' => ['Account Manager', 'Operations Manager'], 'note' => 'Review active retainers, client service coverage, and open opportunities.', 'action' => 'Assign client service owners and confirm next-quarter scopes.'],
            ['date' => '2026-02-01', 'title' => 'Outdoor Campaign Booking Window', 'title_ar' => 'نافذة حجز حملات الأوت دور', 'type' => 'traffic', 'country' => 'Saudi Arabia', 'audience' => ['employee', 'traffic', 'marketing'], 'roles' => ['Traffic Manager', 'Media Zone', 'Account Manager'], 'note' => 'Outdoor media, mall branding, city screens, and OOH supplier booking readiness.', 'action' => 'Collect campaign specs, artwork deadlines, locations, and supplier confirmations.'],
            ['date' => '2026-02-18', 'title' => 'Ramadan Campaign Season', 'title_ar' => 'موسم حملات رمضان', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Account Manager', 'Creative', 'Traffic Manager', 'Media Zone'], 'note' => 'Prepare Ramadan content, offers, production windows, and approval deadlines.', 'action' => 'Push client reminders for briefs, campaign budgets, and production approvals.'],
            ['date' => '2026-02-22', 'title' => 'Saudi Founding Day', 'title_ar' => 'يوم التأسيس السعودي', 'type' => 'national', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Creative', 'Motion', 'Content', 'Account Manager'], 'note' => 'National creative, retail, social, and activation planning window.', 'action' => 'Prepare national-day style content, adaptations, and publishing checks.'],
            ['date' => '2026-03-01', 'title' => 'Monthly Social Calendar Lock', 'title_ar' => 'إقفال تقويم السوشال الشهري', 'type' => 'operations', 'country' => 'Global', 'audience' => ['employee', 'traffic', 'marketing', 'client_service'], 'roles' => ['Content', 'Account Manager', 'Traffic Manager'], 'note' => 'Monthly content calendars should be locked before production and scheduling.', 'action' => 'Confirm approvals, copy decks, visual routes, and publishing dates.'],
            ['date' => '2026-03-20', 'title' => 'Eid Al-Fitr Content Window', 'title_ar' => 'نافذة محتوى عيد الفطر', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Creative', 'Motion', 'Account Manager'], 'note' => 'Eid greetings, offers, production delivery, and social publishing window.', 'action' => 'Ensure final assets are reviewed by client service before client portal delivery.'],
            ['date' => '2026-04-10', 'title' => 'Summer Campaign Window', 'title_ar' => 'نافذة حملات الصيف', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'marketing'], 'roles' => ['Marketing', 'Account Manager', 'Creative'], 'note' => 'Retail, FMCG, food, travel, and activation planning for summer offers.', 'action' => 'Prepare proposal routes and campaign packages for active clients.'],
            ['date' => '2026-05-01', 'title' => 'Media Booking Deadline', 'title_ar' => 'موعد حجز الميديا', 'type' => 'traffic', 'country' => 'Saudi Arabia', 'audience' => ['employee', 'traffic', 'marketing'], 'roles' => ['Media Zone', 'Traffic Manager', 'Account Manager'], 'note' => 'Book paid media, outdoor, production vendors, and supplier windows.', 'action' => 'Escalate missing supplier confirmations and production timelines.'],
            ['date' => '2026-05-26', 'title' => 'Hajj Campaign Readiness', 'title_ar' => 'جاهزية حملات الحج', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Account Manager', 'Content', 'Traffic Manager'], 'note' => 'Hajj-related logistics, messages, content moderation, and delivery planning.', 'action' => 'Validate sensitive wording, publishing dates, and client approvals.'],
            ['date' => '2026-05-27', 'title' => 'Eid Al-Adha Content Window', 'title_ar' => 'نافذة محتوى عيد الأضحى', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Creative', 'Motion', 'Account Manager'], 'note' => 'Eid Al-Adha greetings, retail campaigns, client approvals, and publishing.', 'action' => 'Prepare delivery links, archive assets, and update client portal status.'],
            ['date' => '2026-06-15', 'title' => 'Back to School Retail Window', 'title_ar' => 'نافذة حملات العودة للمدارس', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'marketing'], 'roles' => ['Marketing', 'Creative', 'Account Manager'], 'note' => 'Retail, FMCG, stationery, electronics, and family campaign planning.', 'action' => 'Prepare service bundles and proposal templates for client outreach.'],
            ['date' => '2026-07-01', 'title' => 'Mid-Year Client Health Check', 'title_ar' => 'مراجعة منتصف العام للعملاء', 'type' => 'management', 'country' => 'Global', 'audience' => ['employee', 'management', 'client_service'], 'roles' => ['Operations Manager', 'Account Manager'], 'note' => 'Review client satisfaction, open projects, overdue approvals, and opportunity pipeline.', 'action' => 'Update CRM notes and flag accounts needing senior follow-up.'],
            ['date' => '2026-09-23', 'title' => 'Saudi National Day', 'title_ar' => 'اليوم الوطني السعودي', 'type' => 'national', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Creative', 'Motion', 'Media Zone', 'Traffic Manager'], 'note' => 'Major Saudi campaign moment for brand films, outdoor, digital, activations, and retail.', 'action' => 'Start briefs early, confirm production capacity, and monitor approval deadlines.'],
            ['date' => '2026-10-20', 'title' => 'White Friday / Mega Sale Readiness', 'title_ar' => 'جاهزية حملات التخفيضات الكبرى', 'type' => 'seasonal', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing'], 'roles' => ['Marketing', 'Media Zone', 'Creative', 'Account Manager'], 'note' => 'Retail and ecommerce campaign planning for sale periods and media bursts.', 'action' => 'Prepare media, offers, landing-page assets, and performance reporting plan.'],
            ['date' => '2026-11-20', 'title' => 'End of Year Campaign Planning', 'title_ar' => 'تخطيط حملات نهاية العام', 'type' => 'planning', 'country' => 'Saudi Arabia', 'audience' => ['client', 'employee', 'traffic', 'marketing', 'management'], 'roles' => ['Operations Manager', 'Account Manager', 'Traffic Manager'], 'note' => 'Plan Q1, annual retainers, reporting, archive cleanup, and renewal proposals.', 'action' => 'Prepare year-end reports, renewal decks, and client roadmap sessions.'],
            ['date' => '2026-12-10', 'title' => 'Archive Cleanup & Delivery Audit', 'title_ar' => 'تنظيف الأرشيف ومراجعة التسليمات', 'type' => 'operations', 'country' => 'Global', 'audience' => ['employee', 'traffic', 'client_service'], 'roles' => ['Archive Officer', 'Client Service', 'Traffic Manager'], 'note' => 'Audit delivered files, WeTransfer links, final folders, and client portal visibility.', 'action' => 'Move completed work to archive and validate delivery links.'],
        ];
    }
}
