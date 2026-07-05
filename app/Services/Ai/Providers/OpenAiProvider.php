<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\AiProviderException;
use App\Services\Ai\Contracts\AiProvider;
use Illuminate\Support\Facades\Http;

class OpenAiProvider implements AiProvider
{
    use HandlesProviderErrors;

    public function generate(string $systemPrompt, string $prompt): string
    {
        $response = $this->request(
            fn () => Http::acceptJson()
                ->withToken(config('services.ai.providers.openai.api_key'))
                ->timeout(60)
                ->post(rtrim(config('services.ai.providers.openai.base_url'), '/').'/responses', [
                    'model' => $this->model(),
                    'instructions' => $systemPrompt,
                    'input' => $prompt,
                ]),
            'OpenAI',
        );

        $text = collect($response['output'] ?? [])
            ->flatMap(fn (array $item) => $item['content'] ?? [])
            ->where('type', 'output_text')
            ->pluck('text')
            ->filter()
            ->implode("\n");

        if ($text === '') {
            throw new AiProviderException('OpenAI returned no text output.');
        }

        return $text;
    }

    public function model(): string
    {
        return config('services.ai.providers.openai.model');
    }
}
