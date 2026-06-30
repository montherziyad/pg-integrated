<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::insert([
            [
                'name' => 'Jeddah HQ',
                'code' => 'JED',
                'country' => 'Saudi Arabia',
                'city' => 'Jeddah',
                'is_active' => true,
            ],
            [
                'name' => 'Riyadh',
                'code' => 'RUH',
                'country' => 'Saudi Arabia',
                'city' => 'Riyadh',
                'is_active' => true,
            ],
            [
                'name' => 'Cairo',
                'code' => 'CAI',
                'country' => 'Egypt',
                'city' => 'Cairo',
                'is_active' => true,
            ],
            [
                'name' => 'Beirut',
                'code' => 'BEY',
                'country' => 'Lebanon',
                'city' => 'Beirut',
                'is_active' => true,
            ],
        ]);
    }
}