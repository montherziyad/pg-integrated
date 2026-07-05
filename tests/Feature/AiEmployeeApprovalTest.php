<?php

use App\Models\AiApprovalSuggestion;
use App\Models\CrmCompany;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;

it('creates CRM suggestions without applying them until approved', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('ai-employee.crm.suggest-lead'), [
            'company_name' => 'LinkedIn Prospect Co',
            'linkedin_url' => 'https://www.linkedin.com/company/example',
            'industry' => 'Retail',
            'country' => 'Saudi Arabia',
            'notes' => 'Potential lead from approved research.',
        ])
        ->assertRedirect();

    expect(AiApprovalSuggestion::query()->where('title', 'Create CRM lead: LinkedIn Prospect Co')->exists())->toBeTrue()
        ->and(CrmCompany::query()->where('name', 'LinkedIn Prospect Co')->exists())->toBeFalse();

    $suggestion = AiApprovalSuggestion::query()->firstOrFail();

    $this->actingAs($user)
        ->post(route('ai-employee.suggestions.approve', $suggestion))
        ->assertRedirect();

    expect(CrmCompany::query()->where('name', 'LinkedIn Prospect Co')->exists())->toBeTrue()
        ->and($suggestion->fresh()->status)->toBe('approved');
});

it('drafts support replies without posting them until approved', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::create([
        'subject' => 'Need campaign update',
        'priority' => 'normal',
        'channel' => 'website',
    ]);

    $ticket->messages()->create([
        'sender_type' => 'customer',
        'message' => 'Can you update me?',
    ]);

    $this->actingAs($user)
        ->post(route('ai-employee.support.suggest-reply', $ticket))
        ->assertRedirect();

    expect(SupportMessage::query()->where('sender_type', 'agent')->exists())->toBeFalse();

    $suggestion = AiApprovalSuggestion::query()->where('type', 'support_reply_draft')->firstOrFail();

    $this->actingAs($user)
        ->post(route('ai-employee.suggestions.approve', $suggestion))
        ->assertRedirect();

    expect(SupportMessage::query()->where('sender_type', 'agent')->exists())->toBeTrue();
});
