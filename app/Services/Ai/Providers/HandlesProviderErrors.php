<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\AiProviderException;
use Closure;
use Illuminate\Http\Client\Response;
use Throwable;

trait HandlesProviderErrors
{
    protected function request(Closure $callback, string $provider): array
    {
        try {
            /** @var Response $response */
            $response = $callback();

            return $response->throw()->json();
        } catch (Throwable $exception) {
            report($exception);

            throw new AiProviderException(
                "{$provider} request failed. Verify the API key and model, then try again.",
                previous: $exception,
            );
        }
    }
}
