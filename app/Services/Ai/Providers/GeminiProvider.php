<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\AiProviderException;
use App\Services\Ai\Contracts\AiProvider;
use Illuminate\Support\Facades\Http;

class GeminiProvider implements AiProvider
{
    use HandlesProviderErrors;

    public function generate(string $systemPrompt, string $prompt): string
    {
        $url = sprintf(
            '%s/models/%s:generateContent',
            rtrim(config('services.ai.providers.gemini.base_url'), '/'),
            $this->model(),
        );

        $response = $this->request(
            fn () => Http::acceptJson()
                ->withHeader('x-goog-api-key', config('services.ai.providers.gemini.api_key'))
                ->timeout(60)
                ->post($url, [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => [[
                        'role' => 'user',
                        'parts' => [['text' => $prompt]],
                    ]],
                ]),
            'Gemini',
        );

        $text = collect(data_get($response, 'candidates.0.content.parts', []))
            ->pluck('text')
            ->filter()
            ->implode("\n");

        if ($text === '') {
            throw new AiProviderException('Gemini returned no text output.');
        }

        return $text;
    }

    public function model(): string
    {
        return config('services.ai.providers.gemini.model');
    }
}
