<?php

namespace App\Modules\Settings\Services;

use App\Modules\Settings\Repositories\SettingRepository;
use Illuminate\Support\Facades\DB;

class SettingService
{
    public function __construct(
        protected SettingRepository $repository
    ) {}

    public function all(): array
    {
        return array_merge($this->defaults(), $this->repository->values());
    }

    public function update(array $data): void
    {
        DB::transaction(function () use ($data) {
            foreach ($this->definitions() as $key => $definition) {
                if (! array_key_exists($key, $data)) {
                    continue;
                }

                $value = $definition['type'] === 'boolean'
                    ? (bool) $data[$key]
                    : ($data[$key] ?? null);

                $this->repository->put($key, $value, $definition['group'], $definition['type']);
            }
        });
    }

    private function defaults(): array
    {
        return [
            'company_name' => 'PG Integrated',
            'company_email' => null,
            'company_phone' => null,
            'timezone' => 'Asia/Riyadh',
            'date_format' => 'Y-m-d',
            'default_capacity_hours' => 8,
            'job_number_prefix' => 'JOB',
            'email_notifications' => '1',
            'dashboard_logo_path' => '/prd-assets/PGi-Logo.png',
            'brand_primary_color' => '#020617',
            'appearance_mode' => 'light',
            'default_language' => 'en',
            'translation_provider' => 'manual',
            'openai_translation_model' => 'gpt-4.1-mini',
            'dropbox_api_enabled' => '0',
            'dropbox_app_key' => null,
            'dropbox_default_folder' => null,
            'wetransfer_api_enabled' => '0',
            'wetransfer_default_email' => null,
            'wetransfer_default_message' => null,
            'outlook_enabled' => '0',
            'outlook_tenant_id' => null,
            'outlook_client_id' => null,
            'outlook_mailbox_address' => null,
            'outlook_company_domain' => 'pgintegrated.com,mediazone.com',
            'outlook_job_number_pattern' => '\\b(?:JOB-)?\\d{5}\\b',
            'outlook_allowed_extensions' => 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,psd,ai,aep,png,jpg,jpeg,mp4,mov',
            'outlook_max_attachment_mb' => 50,
            'outlook_subscription_id' => null,
            'outlook_subscription_expires_at' => null,
        ];
    }

    private function definitions(): array
    {
        return [
            'company_name' => ['group' => 'company', 'type' => 'string'],
            'company_email' => ['group' => 'company', 'type' => 'string'],
            'company_phone' => ['group' => 'company', 'type' => 'string'],
            'timezone' => ['group' => 'localization', 'type' => 'string'],
            'date_format' => ['group' => 'localization', 'type' => 'string'],
            'default_capacity_hours' => ['group' => 'operations', 'type' => 'integer'],
            'job_number_prefix' => ['group' => 'operations', 'type' => 'string'],
            'email_notifications' => ['group' => 'notifications', 'type' => 'boolean'],
            'dashboard_logo_path' => ['group' => 'branding', 'type' => 'string'],
            'brand_primary_color' => ['group' => 'branding', 'type' => 'string'],
            'appearance_mode' => ['group' => 'appearance', 'type' => 'string'],
            'default_language' => ['group' => 'localization', 'type' => 'string'],
            'translation_provider' => ['group' => 'localization', 'type' => 'string'],
            'openai_translation_model' => ['group' => 'localization', 'type' => 'string'],
            'dropbox_api_enabled' => ['group' => 'integrations', 'type' => 'boolean'],
            'dropbox_app_key' => ['group' => 'integrations', 'type' => 'string'],
            'dropbox_default_folder' => ['group' => 'integrations', 'type' => 'string'],
            'wetransfer_api_enabled' => ['group' => 'integrations', 'type' => 'boolean'],
            'wetransfer_default_email' => ['group' => 'integrations', 'type' => 'string'],
            'wetransfer_default_message' => ['group' => 'integrations', 'type' => 'string'],
            'outlook_enabled' => ['group' => 'outlook', 'type' => 'boolean'],
            'outlook_tenant_id' => ['group' => 'outlook', 'type' => 'string'],
            'outlook_client_id' => ['group' => 'outlook', 'type' => 'string'],
            'outlook_mailbox_address' => ['group' => 'outlook', 'type' => 'string'],
            'outlook_company_domain' => ['group' => 'outlook', 'type' => 'string'],
            'outlook_job_number_pattern' => ['group' => 'outlook', 'type' => 'string'],
            'outlook_allowed_extensions' => ['group' => 'outlook', 'type' => 'string'],
            'outlook_max_attachment_mb' => ['group' => 'outlook', 'type' => 'integer'],
        ];
    }
}
