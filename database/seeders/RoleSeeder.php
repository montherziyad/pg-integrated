<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Support\RoleScreenPermissions;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'code' => 'SUPER_ADMIN',
                'description' => 'System Administrator',
                'is_active' => true,
            ],
            [
                'name' => 'Operations Manager',
                'code' => 'OPERATIONS_MANAGER',
                'description' => 'Operations Manager',
                'is_active' => true,
            ],
            [
                'name' => 'Traffic Manager',
                'code' => 'TRAFFIC_MANAGER',
                'description' => 'Traffic Manager',
                'is_active' => true,
            ],
            [
                'name' => 'Client Service',
                'code' => 'ACCOUNT_MANAGER',
                'description' => 'Client Service daily account follow-up and client coordination.',
                'is_active' => true,
            ],
            [
                'name' => 'Client Service Manager',
                'code' => 'CLIENT_SERVICE_MANAGER',
                'description' => 'Reviews final output, manages client handover, and publishes approved delivery links to the client portal.',
                'is_active' => true,
            ],
            [
                'name' => 'Designer',
                'code' => 'DESIGNER',
                'description' => 'Graphic Designer',
                'is_active' => true,
            ],
            [
                'name' => 'Senior Designer',
                'code' => 'SENIOR_DESIGNER',
                'description' => 'Senior Designer',
                'is_active' => true,
            ],
            [
                'name' => 'Content Writer',
                'code' => 'CONTENT_WRITER',
                'description' => 'Content Writer',
                'is_active' => true,
            ],
            [
                'name' => 'Motion Designer',
                'code' => 'MOTION_DESIGNER',
                'description' => 'Motion Graphics Designer',
                'is_active' => true,
            ],
            [
                'name' => 'QA Reviewer',
                'code' => 'QA_REVIEWER',
                'description' => 'Quality Assurance Reviewer',
                'is_active' => true,
            ],
            [
                'name' => 'Archive Officer',
                'code' => 'ARCHIVE_OFFICER',
                'description' => 'Archive Officer',
                'is_active' => true,
            ],
            [
                'name' => 'General Manager',
                'code' => 'GENERAL_MANAGER',
                'description' => 'General Manager with company-level oversight and management access.',
                'is_active' => true,
            ],
            [
                'name' => 'HR',
                'code' => 'HR',
                'description' => 'Human Resources role for employee records, recruitment, and internal follow-up.',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            $existingRole = Role::query()->where('code', $role['code'])->first();

            if (! $existingRole || blank($existingRole->screen_permissions)) {
                $role['screen_permissions'] = RoleScreenPermissions::defaultsForRole($role['code']);
            }

            Role::query()->updateOrCreate(
                ['code' => $role['code']],
                $role,
            );
        }
    }
}
