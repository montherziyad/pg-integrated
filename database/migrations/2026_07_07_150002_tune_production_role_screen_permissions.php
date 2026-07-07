<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Role::query()
            ->whereIn('code', ['DESIGNER', 'SENIOR_DESIGNER', 'CONTENT_WRITER', 'MOTION_DESIGNER', 'QA_REVIEWER'])
            ->get()
            ->each(function (Role $role): void {
                $permissions = $role->screen_permissions ?: [];
                unset($permissions['team_workload'], $permissions['archive']);
                $permissions['employee_handover'] = true;

                $role->forceFill(['screen_permissions' => $permissions])->save();
            });
    }

    public function down(): void
    {
        Role::query()
            ->whereIn('code', ['DESIGNER', 'SENIOR_DESIGNER', 'CONTENT_WRITER', 'MOTION_DESIGNER', 'QA_REVIEWER'])
            ->get()
            ->each(function (Role $role): void {
                $permissions = $role->screen_permissions ?: [];
                $permissions['team_workload'] = true;
                $permissions['archive'] = true;

                $role->forceFill(['screen_permissions' => $permissions])->save();
            });
    }
};
