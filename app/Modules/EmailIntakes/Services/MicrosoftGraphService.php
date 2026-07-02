<?php

namespace App\Modules\EmailIntakes\Services;

use App\Models\Setting;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MicrosoftGraphService
{
    public function fetchMessage(string $messageId): array
    {
        $mailbox = $this->setting('outlook_mailbox_address', config('services.outlook.mailbox'));
        throw_unless($mailbox, RuntimeException::class, 'Outlook mailbox is not configured.');

        return $this->client()
            ->get('https://graph.microsoft.com/v1.0/users/'.rawurlencode($mailbox).'/messages/'.rawurlencode($messageId), [
                '$select' => 'id,internetMessageId,conversationId,subject,body,bodyPreview,from,toRecipients,ccRecipients,receivedDateTime,hasAttachments',
                '$expand' => 'attachments',
            ])
            ->throw()
            ->json();
    }

    public function testConnection(): array
    {
        $mailbox = $this->setting('outlook_mailbox_address', config('services.outlook.mailbox'));
        throw_unless($mailbox, RuntimeException::class, 'Outlook mailbox is not configured.');

        return $this->client()
            ->get('https://graph.microsoft.com/v1.0/users/'.rawurlencode($mailbox), [
                '$select' => 'id,displayName,mail,userPrincipalName',
            ])
            ->throw()
            ->json();
    }

    public function createSubscription(string $notificationUrl): array
    {
        $mailbox = $this->setting('outlook_mailbox_address', config('services.outlook.mailbox'));
        $clientState = config('services.outlook.client_state');
        throw_unless($mailbox && $clientState, RuntimeException::class, 'Mailbox or webhook client state is not configured.');

        return $this->client()
            ->post('https://graph.microsoft.com/v1.0/subscriptions', [
                'changeType' => 'created',
                'notificationUrl' => $notificationUrl,
                'resource' => "/users/{$mailbox}/mailFolders('inbox')/messages",
                'expirationDateTime' => now('UTC')->addDays(6)->toIso8601String(),
                'clientState' => $clientState,
                'latestSupportedTlsVersion' => 'v1_2',
            ])
            ->throw()
            ->json();
    }

    private function client(): PendingRequest
    {
        return Http::withToken($this->accessToken())
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 500);
    }

    private function accessToken(): string
    {
        return Cache::remember('outlook_graph_access_token', 3000, function () {
            $tenantId = $this->setting('outlook_tenant_id', config('services.outlook.tenant_id'));
            $clientId = $this->setting('outlook_client_id', config('services.outlook.client_id'));
            $clientSecret = config('services.outlook.client_secret');
            throw_unless($tenantId && $clientId && $clientSecret, RuntimeException::class, 'Microsoft Graph credentials are incomplete.');

            $response = Http::asForm()
                ->timeout(20)
                ->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => 'https://graph.microsoft.com/.default',
                    'grant_type' => 'client_credentials',
                ])
                ->throw()
                ->json();

            return $response['access_token'];
        });
    }

    private function setting(string $key, mixed $default = null): mixed
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }
}
