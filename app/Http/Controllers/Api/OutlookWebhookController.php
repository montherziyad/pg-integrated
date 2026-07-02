<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutlookWebhookRequest;
use App\Jobs\ProcessOutlookMessage;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class OutlookWebhookController extends Controller
{
    public function __invoke(OutlookWebhookRequest $request): Response
    {
        if ($request->filled('validationToken')) {
            return response($request->query('validationToken'), 200)
                ->header('Content-Type', 'text/plain');
        }

        $expectedClientState = (string) config('services.outlook.client_state');

        if (blank($expectedClientState)) {
            return response('', 503);
        }

        foreach ($request->validated('value', []) as $notification) {
            if (! hash_equals($expectedClientState, (string) ($notification['clientState'] ?? ''))) {
                continue;
            }

            $messageId = data_get($notification, 'resourceData.id')
                ?: Str::afterLast($notification['resource'], '/');

            if (filled($messageId)) {
                ProcessOutlookMessage::dispatch($messageId);
            }
        }

        return response('', 202);
    }
}
