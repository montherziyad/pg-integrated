<?php

use App\Models\AiInteraction;
use App\Models\CmsPage;
use App\Models\CrmActivity;
use App\Models\CrmCompany;
use App\Models\CrmContact;
use App\Models\CrmTask;
use App\Models\MarketingCampaign;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;

it('renders the public website from published CMS content', function () {
    CmsPage::create([
        'key' => 'home',
        'title' => 'PG Integrated',
        'slug' => 'home',
        'is_published' => true,
        'sections' => [
            'hero' => [
                'eyebrow' => 'Creative Agency',
                'title' => 'A CMS powered homepage',
                'body' => 'Editable from the dashboard.',
            ],
        ],
        'seo' => ['title' => 'PG Integrated Test'],
    ]);

    $this->get(route('website.home'))
        ->assertOk()
        ->assertSee('A CMS powered homepage')
        ->assertSee('PG Integrated Test');
});

it('does not expose unpublished CMS pages publicly', function () {
    $page = CmsPage::create([
        'key' => 'private',
        'title' => 'Private page',
        'slug' => 'private',
        'is_published' => false,
    ]);

    $this->get(route('website.page', $page))->assertNotFound();
});

it('renders the growth platform pages for an authenticated employee', function (string $routeName) {
    $this->actingAs(User::factory()->create())
        ->get(route($routeName))
        ->assertOk();
})->with([
    'CMS list' => 'admin.cms.index',
    'CMS create' => 'admin.cms.create',
    'CRM list' => 'crm.index',
    'CRM create' => 'crm.create',
    'Support list' => 'support.index',
    'Support create' => 'support.create',
    'AI workspace' => 'ai.workspace',
    'Marketing list' => 'marketing.index',
    'Marketing create' => 'marketing.create',
]);

it('creates and updates a CMS page with structured JSON content', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.cms.store'), [
            'key' => 'home',
            'title' => 'Home',
            'slug' => 'home',
            'sections' => '{"hero":{"title":"Welcome"}}',
            'seo' => '{"description":"PG Integrated"}',
            'is_published' => '1',
        ])
        ->assertRedirect(route('admin.cms.index'));

    $page = CmsPage::query()->firstOrFail();

    expect($page->sections)->toBe(['hero' => ['title' => 'Welcome']])
        ->and($page->seo)->toBe(['description' => 'PG Integrated'])
        ->and($page->is_published)->toBeTrue();

    $this->actingAs($user)
        ->get(route('admin.cms.edit', $page))
        ->assertOk();

    $this->actingAs($user)
        ->put(route('admin.cms.update', $page), [
            'key' => 'home',
            'title' => 'Homepage',
            'slug' => 'home',
            'sections' => '{"hero":{"title":"Updated"}}',
            'seo' => '{}',
        ])
        ->assertRedirect(route('admin.cms.index'));

    expect($page->fresh()->title)->toBe('Homepage')
        ->and($page->fresh()->is_published)->toBeFalse();
});

it('runs the CRM company, contact, activity, and task workflow', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('crm.store'), [
            'name' => 'Acme Company',
            'industry' => 'Retail',
            'country' => 'Saudi Arabia',
            'website' => 'https://example.com',
            'status' => 'new',
            'lead_score' => 70,
            'owner_id' => $user->id,
            'contact_name' => 'Sara',
            'contact_email' => 'sara@example.com',
        ])
        ->assertRedirect();

    $company = CrmCompany::query()->firstOrFail();

    expect(CrmContact::query()->where('company_id', $company->id)->count())->toBe(1);

    $this->actingAs($user)
        ->post(route('crm.activities.store', $company), [
            'type' => 'follow_up',
            'channel' => 'email',
            'summary' => 'Sent introduction',
        ])
        ->assertRedirect();

    $this->actingAs($user)
        ->post(route('crm.tasks.store', $company), [
            'title' => 'Call the client',
            'assigned_to' => $user->id,
        ])
        ->assertRedirect();

    expect(CrmActivity::query()->count())->toBe(1)
        ->and(CrmTask::query()->count())->toBe(1)
        ->and($company->fresh()->last_contacted_at)->not->toBeNull();
});

it('runs the support ticket and reply workflow', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('support.store'), [
            'subject' => 'Website support',
            'priority' => 'high',
            'channel' => 'email',
            'assigned_to' => $user->id,
            'message' => 'The customer needs help.',
        ])
        ->assertRedirect();

    $ticket = SupportTicket::query()->firstOrFail();

    $this->actingAs($user)
        ->get(route('support.show', $ticket))
        ->assertOk();

    $this->actingAs($user)
        ->post(route('support.reply', $ticket), [
            'sender_type' => 'agent',
            'message' => 'We are reviewing your request.',
        ])
        ->assertRedirect();

    expect($ticket->ticket_number)->toStartWith('PG-TCK-')
        ->and(SupportMessage::query()->count())->toBe(2);
});

it('creates a marketing campaign linked to its employee', function () {
    $user = User::factory()->create();
    $company = CrmCompany::create([
        'name' => 'Prospect Company',
        'status' => 'new',
    ]);
    $contact = CrmContact::create([
        'company_id' => $company->id,
        'name' => 'Prospect Contact',
        'email' => 'prospect@example.com',
    ]);

    $response = $this->actingAs($user)
        ->post(route('marketing.store'), [
            'name' => 'Agency Outreach',
            'objective' => 'Introduce PG Integrated services.',
            'email_subject' => 'PG Integrated introduction',
            'message_template' => 'Hello {{ name }}',
            'recipient_ids' => [$contact->id],
        ]);

    $campaign = MarketingCampaign::query()->firstOrFail();
    $response->assertRedirect(route('marketing.show', $campaign));

    expect($campaign->created_by)->toBe($user->id)
        ->and($campaign->channel)->toBe('outlook_email')
        ->and($campaign->status)->toBe('draft')
        ->and($campaign->recipients()->count())->toBe(1)
        ->and(AiInteraction::query()->count())->toBe(0);
});
