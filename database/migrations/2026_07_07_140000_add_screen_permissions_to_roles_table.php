<?php

use App\Models\Role;
use App\Support\RoleScreenPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->json('screen_permissions')->nullable()->after('description');
        });

        Role::query()->get()->each(function (Role $role): void {
            $role->forceFill([
                'screen_permissions' => RoleScreenPermissions::defaultsForRole($role->code),
            ])->save();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('screen_permissions');
        });
    }
};
