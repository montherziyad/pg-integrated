<?php

namespace App\Http\Controllers;

use App\Modules\EmailIntakes\Services\MicrosoftGraphService;
use App\Modules\Settings\Repositories\SettingRepository;
use Throwable;

class OutlookConnectionController extends Controller
{
    public function test(MicrosoftGraphService $graph)
    {
        try {
            $mailbox = $graph->testConnection();

            return back()->with('success', 'Outlook connected: '.($mailbox['displayName'] ?? $mailbox['userPrincipalName'] ?? 'Mailbox'));
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['outlook' => 'Outlook connection failed: '.$exception->getMessage()]);
        }
    }

    public function subscribe(MicrosoftGraphService $graph, SettingRepository $settings)
    {
        try {
            $subscription = $graph->createSubscription(route('api.outlook.webhook'));
            $settings->put('outlook_subscription_id', $subscription['id'] ?? null, 'outlook');
            $settings->put('outlook_subscription_expires_at', $subscription['expirationDateTime'] ?? null, 'outlook');

            return back()->with('success', 'Outlook webhook subscription created successfully.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['outlook' => 'Subscription failed: '.$exception->getMessage()]);
        }
    }
}
