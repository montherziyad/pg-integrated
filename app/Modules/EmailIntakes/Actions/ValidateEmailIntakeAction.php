<?php

namespace App\Modules\EmailIntakes\Actions;

use App\Models\EmailIntake;
use App\Models\EmailIntakeTrafficMember;
use App\Models\Setting;
use App\Models\User;

class ValidateEmailIntakeAction
{
    public function execute(EmailIntake $intake): bool
    {
        $intake->validations()->delete();

        $senderEmail = strtolower((string) $intake->sender_email);
        $domains = collect(explode(',', strtolower((string) $this->setting(
            'outlook_company_domain',
            'pgintegrated.com,mediazone.com'
        ))))
            ->map(fn ($domain) => ltrim(trim($domain), '@'))
            ->filter()
            ->unique();
        $senderDomainPassed = $domains->contains(
            fn ($domain) => str_ends_with($senderEmail, '@'.$domain)
        );
        $senderMemberPassed = User::whereRaw('LOWER(email) = ?', [$senderEmail])->where('is_active', true)->exists();

        $this->record($intake, 'internal_sender_domain', $senderDomainPassed, $senderDomainPassed ? 'Sender domain accepted.' : 'Sender is outside the approved company domains.');
        $this->record($intake, 'active_company_member', $senderMemberPassed, $senderMemberPassed ? 'Sender is an active member.' : 'Sender email is not an active system user.');

        $pattern = (string) $this->setting('outlook_job_number_pattern', '\\b(?:JOB-)?\\d{5}\\b');
        $content = trim($intake->subject.' '.$intake->body);
        $jobNumber = $this->extractJobNumber($pattern, $content);
        $this->record($intake, 'job_number', filled($jobNumber), filled($jobNumber) ? 'Job number detected: '.$jobNumber : 'No valid job number was detected.');

        $briefPassed = $intake->attachments()->where('is_brief', true)->exists();
        $this->record($intake, 'brief_attachment', $briefPassed, $briefPassed ? 'Brief attachment found.' : 'No valid brief attachment was found.');

        $recipientEmails = collect(array_merge($intake->to_recipients ?? [], $intake->cc_recipients ?? []))
            ->map(fn ($recipient) => strtolower(is_array($recipient) ? ($recipient['email'] ?? '') : (string) $recipient))
            ->filter();

        $trafficEmails = EmailIntakeTrafficMember::where('is_active', true)
            ->pluck('outlook_email')
            ->map(fn ($email) => strtolower($email));

        $trafficPassed = $recipientEmails->intersect($trafficEmails)->isNotEmpty();
        $this->record($intake, 'traffic_recipient', $trafficPassed, $trafficPassed ? 'Traffic member found in To/CC.' : 'No configured Traffic member was found in To/CC.');

        $passed = $senderDomainPassed && $senderMemberPassed && filled($jobNumber) && $briefPassed && $trafficPassed;
        $errors = $intake->validations()->where('passed', false)->pluck('message')->all();

        $intake->update([
            'extracted_job_number' => $jobNumber,
            'validation_passed' => $passed,
            'validation_errors' => $errors,
        ]);

        return $passed;
    }

    private function record(EmailIntake $intake, string $rule, bool $passed, string $message): void
    {
        $intake->validations()->create(compact('rule', 'passed', 'message'));
    }

    private function extractJobNumber(string $pattern, string $content): ?string
    {
        set_error_handler(static fn () => true);
        $matched = preg_match('~'.$pattern.'~i', $content, $matches);
        restore_error_handler();

        return $matched === 1 ? strtoupper($matches[0]) : null;
    }

    private function setting(string $key, mixed $default = null): mixed
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }
}
