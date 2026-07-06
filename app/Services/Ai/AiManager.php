<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\AiProvider;
use App\Services\Ai\Providers\AnthropicProvider;
use App\Services\Ai\Providers\GeminiProvider;
use App\Services\Ai\Providers\OpenAiProvider;

class AiManager
{
    public function generate(string $provider, string $agent, string $prompt): string
    {
        if (! $this->isConfigured($provider)) {
            throw new AiProviderException(
                sprintf('%s is not configured. Add its API key to the environment file.', $this->label($provider))
            );
        }

        return $this->provider($provider)->generate(
            app(PromptBuilder::class)->systemPrompt($agent),
            $prompt,
        );
    }

    public function options(): array
    {
        return collect(config('services.ai.providers'))
            ->map(fn (array $config, string $name) => [
                'name' => $name,
                'label' => $config['label'],
                'model' => $config['model'],
                'configured' => filled($config['api_key']),
            ])
            ->values()
            ->all();
    }

    public function names(): array
    {
        return array_keys(config('services.ai.providers'));
    }

    public function assistantCatalog(): array
    {
        return app(PromptBuilder::class)->catalog();
    }

    public function assistantNames(): array
    {
        return app(PromptBuilder::class)->names();
    }

    public function model(string $provider): string
    {
        return $this->provider($provider)->model();
    }

    private function provider(string $provider): AiProvider
    {
        return match ($provider) {
            'openai' => app(OpenAiProvider::class),
            'gemini' => app(GeminiProvider::class),
            'anthropic' => app(AnthropicProvider::class),
            default => throw new AiProviderException('Unsupported AI provider.'),
        };
    }

    private function isConfigured(string $provider): bool
    {
        return filled(config("services.ai.providers.{$provider}.api_key"));
    }

    private function label(string $provider): string
    {
        return config("services.ai.providers.{$provider}.label", ucfirst($provider));
    }
}
