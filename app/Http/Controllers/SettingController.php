<?php

namespace App\Http\Controllers;

use App\Models\EmailIntakeTrafficMember;
use App\Models\User;
use App\Modules\Settings\Requests\UpdateSettingsRequest;
use App\Modules\Settings\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index()
    {
        return view('admin.settings.index', [
            'settings' => $this->settingService->all(),
        ]);
    }

    public function outlook()
    {
        return view('admin.settings.outlook', [
            'settings' => $this->settingService->all(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'trafficMemberIds' => EmailIntakeTrafficMember::where('is_active', true)->pluck('user_id')->all(),
            'outlookSecretConfigured' => filled(config('services.outlook.client_secret')),
        ]);
    }

    public function resetPage()
    {
        return view('admin.settings.reset-data');
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');

        return back()->with('success', 'System cache cleared successfully.');
    }

    public function resetTestData(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'SUPER_ADMIN', 403);

        $data = $request->validate([
            'reset_targets' => ['required', 'array', 'min:1'],
            'reset_targets.*' => ['string', 'in:email_intake,jobs,clients,teams,users'],
            'confirmation_phrase' => ['required', 'string', 'in:RESET TEST DATA'],
        ]);

        $targets = collect($data['reset_targets'])->unique()->values();

        if ($targets->contains('clients') && ! $targets->contains('jobs')) {
            return back()->withErrors(['reset_targets' => 'Select Jobs when resetting Clients because jobs are connected to clients.']);
        }

        if ($targets->contains('teams') && ! $targets->contains('jobs')) {
            return back()->withErrors(['reset_targets' => 'Select Jobs when resetting Team because assignments are connected to teams.']);
        }

        if ($targets->contains('users') && (! $targets->contains('jobs') || ! $targets->contains('clients'))) {
            return back()->withErrors(['reset_targets' => 'Select Jobs and Clients when resetting User so employee/client ownership links can be cleared safely.']);
        }

        $deleted = [];

        DB::transaction(function () use ($targets, $request, &$deleted): void {
            if ($targets->contains('email_intake')) {
                $deleted['Email Intake'] = $this->deleteTables([
                    'email_intake_validations',
                    'email_intake_attachments',
                    'email_intakes',
                ]);
            }

            if ($targets->contains('jobs')) {
                $deleted['Jobs'] = $this->deleteTables([
                    'job_approvals',
                    'employee_notifications',
                    'revisions',
                    'assets',
                    'job_activities',
                    'job_stage_histories',
                    'job_assignments',
                    'creative_jobs',
                ]);
            }

            if ($targets->contains('clients')) {
                $deleted['Clients'] = $this->deleteTables([
                    'client_project_requests',
                    'client_service_user',
                    'projects',
                    'clients',
                ]);
            }

            if ($targets->contains('teams')) {
                if (Schema::hasTable('users')) {
                    DB::table('users')->update(['team_id' => null]);
                }

                $deleted['Team'] = $this->deleteTables([
                    'teams',
                ]);
            }

            if ($targets->contains('users')) {
                $deleted['User'] = $this->deleteNonSuperAdminUsers($request->user()->id);
            }
        });

        $summary = collect($deleted)
            ->map(fn ($count, $label) => "{$label}: {$count}")
            ->implode(' | ');

        return back()->with('success', 'Selected test data cleared successfully. '.$summary);
    }

    public function update(UpdateSettingsRequest $request)
    {
        $this->settingService->update($request->validated());

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function updateOutlook(UpdateSettingsRequest $request)
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
            ->route('admin.settings.outlook')
            ->with('success', 'Outlook intake settings updated successfully.');
    }

    private function deleteTables(array $tables): int
    {
        $count = 0;

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $count += DB::table($table)->count();
            DB::table($table)->delete();
        }

        return $count;
    }

    private function deleteNonSuperAdminUsers(int $currentUserId): int
    {
        if (Schema::hasTable('clients')) {
            DB::table('clients')->update(['account_manager_id' => null]);
        }

        if (Schema::hasTable('projects')) {
            DB::table('projects')->update(['project_manager_id' => null]);
        }

        foreach ([
            'cms_pages' => ['updated_by'],
            'support_tickets' => ['assigned_to'],
            'support_messages' => ['user_id'],
            'career_jobs' => ['created_by'],
            'career_applications' => ['reviewed_by'],
            'crm_companies' => ['owner_id'],
            'crm_activities' => ['user_id'],
            'crm_tasks' => ['assigned_to'],
            'ai_interactions' => ['user_id'],
            'ai_approval_suggestions' => ['created_by', 'approved_by'],
            'marketing_campaigns' => ['created_by', 'approved_by'],
            'email_intakes' => ['reviewed_by'],
        ] as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($table, $column)) {
                    DB::table($table)->update([$column => null]);
                }
            }
        }

        $this->deleteTables([
            'email_intake_traffic_members',
            'employee_leaves',
        ]);

        $superAdminRoleIds = DB::table('roles')
            ->where('code', 'SUPER_ADMIN')
            ->pluck('id');

        $query = DB::table('users')
            ->where('id', '!=', $currentUserId);

        if ($superAdminRoleIds->isNotEmpty()) {
            $query->whereNotIn('role_id', $superAdminRoleIds);
        }

        $count = $query->count();
        $query->delete();

        return $count;
    }
}
