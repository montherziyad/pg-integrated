<?php

namespace App\Http\Controllers;

use App\Models\EmailIntakeTrafficMember;
use App\Models\User;
use App\Modules\Settings\Requests\UpdateSettingsRequest;
use App\Modules\Settings\Services\SettingService;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index()
    {
        return view('admin.settings.index', [
            'settings' => $this->settingService->all(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'trafficMemberIds' => EmailIntakeTrafficMember::where('is_active', true)->pluck('user_id')->all(),
            'outlookSecretConfigured' => filled(config('services.outlook.client_secret')),
        ]);
    }

    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->validated();
        $trafficMemberIds = $data['traffic_member_ids'] ?? [];
        unset($data['traffic_member_ids']);

        $this->settingService->update($data);

        EmailIntakeTrafficMember::whereNotIn('user_id', $trafficMemberIds)->delete();

        User::whereIn('id', $trafficMemberIds)->get()->each(function (User $user) {
            EmailIntakeTrafficMember::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'outlook_email' => $user->email,
                    'is_active' => true,
                    'receives_notifications' => true,
                ]
            );
        });

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
