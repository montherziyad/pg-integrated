<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $setting = Setting::where('key', 'outlook_company_domain')->first();

        if (! $setting) {
            Setting::create([
                'key' => 'outlook_company_domain',
                'value' => 'pgintegrated.com,mediazone.com',
                'group' => 'outlook',
                'type' => 'string',
            ]);

            return;
        }

        $domains = collect(explode(',', strtolower((string) $setting->value)))
            ->map(fn ($domain) => ltrim(trim($domain), '@'))
            ->filter()
            ->push('pgintegrated.com', 'mediazone.com')
            ->unique()
            ->values()
            ->implode(',');

        $setting->update(['value' => $domains]);
    }

    public function down(): void
    {
        $setting = Setting::where('key', 'outlook_company_domain')->first();

        if (! $setting) {
            return;
        }

        $domains = collect(explode(',', strtolower((string) $setting->value)))
            ->map(fn ($domain) => ltrim(trim($domain), '@'))
            ->filter(fn ($domain) => $domain !== 'mediazone.com')
            ->unique()
            ->values()
            ->implode(',');

        $setting->update(['value' => $domains]);
    }
};
