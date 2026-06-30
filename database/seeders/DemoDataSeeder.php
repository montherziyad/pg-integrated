<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::firstOrCreate(
            ['client_code' => 'DEMO'],
            [
                'name' => 'Demo Client',
                'branch_id' => 1,
                'industry' => 'Advertising',
                'email' => 'demo@example.com',
                'phone' => '0000000000',
                'is_active' => true,
            ]
        );

        Project::firstOrCreate(
            ['project_code' => 'DEMO-2026'],
            [
                'client_id' => $client->id,
                'name' => 'Demo Campaign',
                'description' => 'Test project for PG Integrated',
                'is_active' => true,
            ]
        );
    }
}