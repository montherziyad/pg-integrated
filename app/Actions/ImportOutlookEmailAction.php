<?php

namespace App\Actions;

use App\Models\EmailIntake;
use App\Models\Setting;
use App\Modules\EmailIntakes\Actions\ValidateEmailIntakeAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportOutlookEmailAction
{
    public function __construct(protected ValidateEmailIntakeAction $validator) {}

    public function execute(array $message): EmailIntake
    {
        return DB::transaction(function () use ($message) {
            $intake = EmailIntake::updateOrCreate(
                ['message_id' => $message['id']],
                [
                    'source' => 'OUTLOOK',
                    'conversation_id' => $message['conversationId'] ?? null,
                    'internet_message_id' => $message['internetMessageId'] ?? null,
                    'sender_email' => data_get($message, 'from.emailAddress.address'),
                    'sender_name' => data_get($message, 'from.emailAddress.name'),
                    'to_recipients' => $this->recipients($message['toRecipients'] ?? []),
                    'cc_recipients' => $this->recipients($message['ccRecipients'] ?? []),
                    'subject' => $message['subject'] ?? null,
                    'body' => data_get($message, 'body.content', $message['bodyPreview'] ?? null),
                    'received_at' => $message['receivedDateTime'] ?? null,
                    'has_attachments' => (bool) ($message['hasAttachments'] ?? false),
                    'raw_payload' => $message,
                ]
            );

            $this->storeAttachments($intake, $message['attachments'] ?? []);
            $passed = $this->validator->execute($intake->fresh('attachments'));

            if (! $passed) {
                $reason = $intake->fresh()->validation_errors ?? [];

                $intake->update([
                    'status' => 'IGNORED',
                    'rejection_reason' => '[AUTO_FILTERED] '.implode(' ', $reason),
                    'reviewed_at' => now(),
                    'rejected_at' => now(),
                ]);
            }

            return $intake->fresh(['attachments', 'validations']);
        });
    }

    private function recipients(array $recipients): array
    {
        return collect($recipients)->map(fn ($recipient) => [
            'name' => data_get($recipient, 'emailAddress.name'),
            'email' => strtolower((string) data_get($recipient, 'emailAddress.address')),
        ])->all();
    }

    private function storeAttachments(EmailIntake $intake, array $attachments): void
    {
        $allowed = collect(explode(',', (string) Setting::where('key', 'outlook_allowed_extensions')->value('value')))
            ->map(fn ($extension) => strtolower(trim($extension)))
            ->filter();
        $maxBytes = ((int) (Setting::where('key', 'outlook_max_attachment_mb')->value('value') ?? 50)) * 1024 * 1024;

        foreach ($attachments as $attachment) {
            $name = basename((string) ($attachment['name'] ?? 'attachment')) ?: 'attachment';
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $isInline = (bool) ($attachment['isInline'] ?? false);
            $content = isset($attachment['contentBytes']) ? base64_decode($attachment['contentBytes'], true) : false;
            $size = $attachment['size'] ?? ($content !== false ? strlen($content) : 0);
            $path = null;

            if ($content !== false && ! $isInline && $size <= $maxBytes) {
                $path = 'email-intakes/'.$intake->id.'/'.uniqid().'_'.$name;
                Storage::disk('local')->put($path, $content);
            }

            $intake->attachments()->updateOrCreate(
                ['outlook_attachment_id' => $attachment['id'] ?? null, 'name' => $name],
                [
                    'content_type' => $attachment['contentType'] ?? null,
                    'size' => $size,
                    'is_inline' => $isInline,
                    'is_brief' => ! $isInline && $allowed->contains($extension) && filled($path),
                    'storage_disk' => 'local',
                    'storage_path' => $path,
                    'sha256' => $content !== false ? hash('sha256', $content) : null,
                ]
            );
        }
    }
}
