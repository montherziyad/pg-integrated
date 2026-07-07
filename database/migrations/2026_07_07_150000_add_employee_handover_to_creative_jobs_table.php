<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Role;
use App\Support\RoleScreenPermissions;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creative_jobs', function (Blueprint $table) {
            $table->string('employee_handover_status')->default('not_submitted')->after('delivery_review_status');
            $table->text('employee_handover_link')->nullable()->after('employee_handover_status');
            $table->text('employee_handover_notes')->nullable()->after('employee_handover_link');
            $table->foreignId('employee_handover_submitted_by')->nullable()->after('employee_handover_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('employee_handover_submitted_at')->nullable()->after('employee_handover_submitted_by');
        });

        Role::query()->get()->each(function (Role $role): void {
            $permissions = $role->screen_permissions ?: [];
            $defaults = RoleScreenPermissions::defaultsForRole($role->code);

            if (data_get($defaults, 'employee_handover')) {
                $permissions['employee_handover'] = data_get($permissions, 'employee_handover', true);
            }

            $role->forceFill(['screen_permissions' => $permissions])->save();
        });
    }

    public function down(): void
    {
        Schema::table('creative_jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_handover_submitted_by');
            $table->dropColumn([
                'employee_handover_status',
                'employee_handover_link',
                'employee_handover_notes',
                'employee_handover_submitted_at',
            ]);
        });
    }
};
