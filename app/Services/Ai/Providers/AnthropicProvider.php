<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\AiProviderException;
use App\Services\Ai\Contracts\AiProvider;
use Illuminate\Support\Facades\Http;

class AnthropicProvider implements AiProvider
{
    use HandlesProviderErrors;

    public function generate(string $systemPrompt, string $prompt): string
    {
        $response = $this->request(
            fn () => Http::acceptJson()
                ->withHeaders([
                    'x-api-key' => config('services.ai.providers.anthropic.api_key'),
                    'anthropic-version' => '2023-06-01',
                ])
                ->timeout(60)
                ->post(rtrim(config('services.ai.providers.anthropic.base_url'), '/').'/messages', [
                    'model' => $this->model(),
                    'max_tokens' => 1500,
                    'system' => $systemPrompt,
                    'messages' => [[
                        'role' => 'user',
                        'content' => $prompt,
                    ]],
                ]),
            'Claude',
        );

        $text = collect($response['content'] ?? [])
            ->where('type', 'text')
            ->pluck('text')
            ->filter()
            ->implode("\n");

        if ($text === '') {
            throw new AiProviderException('Claude returned no text output.');
        }

        return $text;
    }

    public function model(): string
    {
        return config('services.ai.providers.anthropic.model');
    }
}
