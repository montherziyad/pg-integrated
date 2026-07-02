<?php

namespace App\Modules\Settings\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function values(): array
    {
        return Setting::query()->pluck('value', 'key')->all();
    }

    public function put(string $key, mixed $value, string $group, string $type = 'string'): Setting
    {
        return Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_bool($value) ? ($value ? '1' : '0') : $value,
                'group' => $group,
                'type' => $type,
            ]
        );
    }
}
