<?php

use App\Models\AiInteraction;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('generates and records a draft with the selected provider', function () {
    config()->set('services.ai.providers.openai.api_key', 'test-key');
    config()->set('services.ai.providers.openai.model', 'gpt-test');

    Http::fake([
        'api.openai.com/v1/responses' => Http::response([
            'output' => [[
                'type' => 'message',
                'content' => [[
                    'type' => 'output_text',
                    'text' => 'A useful support reply.',
                ]],
            ]],
        ]),
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('ai.draft'), [
            'provider' => 'openai',
            'agent' => 'support',
            'prompt' => 'Please reply to this customer.',
        ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status');

    $interaction = AiInteraction::query()->firstOrFail();

    expect($interaction->response)->toBe('A useful support reply.')
        ->and($interaction->meta['status'])->toBe('completed')
        ->and($interaction->meta['model'])->toBe('gpt-test');
});

it('records a safe failure when the provider is not configured', function () {
    config()->set('services.ai.providers.gemini.api_key', null);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('ai.workspace'))
        ->post(route('ai.draft'), [
            'provider' => 'gemini',
            'agent' => 'marketing',
            'prompt' => 'Write an introduction.',
        ])
        ->assertRedirect(route('ai.workspace'))
        ->assertSessionHasErrors('provider');

    expect(AiInteraction::query()->firstOrFail()->meta['status'])->toBe('failed');
});

it('generates a Gemini draft through the shared AI workspace', function () {
    config()->set('services.ai.providers.gemini.api_key', 'test-key');
    config()->set('services.ai.providers.gemini.model', 'gemini-test');

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => 'A Gemini marketing draft.']],
                ],
            ]],
        ]),
    ]);

    $this->actingAs(User::factory()->create())
        ->post(route('ai.draft'), [
            'provider' => 'gemini',
            'agent' => 'marketing',
            'prompt' => 'Write an introduction.',
        ])
        ->assertSessionHasNoErrors();

    expect(AiInteraction::query()->firstOrFail()->response)->toBe('A Gemini marketing draft.');
});

it('generates a Claude draft through the shared AI workspace', function () {
    config()->set('services.ai.providers.anthropic.api_key', 'test-key');
    config()->set('services.ai.providers.anthropic.model', 'claude-test');

    Http::fake([
        'api.anthropic.com/v1/messages' => Http::response([
            'content' => [[
                'type' => 'text',
                'text' => 'A Claude proposal draft.',
            ]],
        ]),
    ]);

    $this->actingAs(User::factory()->create())
        ->post(route('ai.draft'), [
            'provider' => 'anthropic',
            'agent' => 'proposal',
            'prompt' => 'Draft a proposal.',
        ])
        ->assertSessionHasNoErrors();

    expect(AiInteraction::query()->firstOrFail()->response)->toBe('A Claude proposal draft.');
});
