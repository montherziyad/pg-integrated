<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['name' => 'Client Service', 'code' => 'CLIENT_SERVICE', 'description' => 'PG client service and account follow-up team.'],
            ['name' => 'Account Management', 'code' => 'ACCOUNT_MANAGEMENT', 'description' => 'Account managers and client relationship owners.'],
            ['name' => 'Traffic Team', 'code' => 'TRAFFIC', 'description' => 'Traffic management and workflow coordination team.'],
            ['name' => 'Creative Team', 'code' => 'CREATIVE', 'description' => 'Creative direction, concepts, copy, and campaign ideas.'],
            ['name' => 'Design Team', 'code' => 'DESIGN', 'description' => 'Graphic design, key visuals, layouts, and adaptations.'],
            ['name' => 'Motion Team', 'code' => 'MOTION', 'description' => 'Motion graphics, animation, and video adaptation team.'],
            ['name' => 'Content Team', 'code' => 'CONTENT', 'description' => 'Content writing, social copy, and editorial production.'],
            ['name' => 'Digital Team', 'code' => 'DIGITAL', 'description' => 'Digital, social media, web, and performance support.'],
            ['name' => 'Production Team', 'code' => 'PRODUCTION', 'description' => 'Production, final artwork, print, POSM, and delivery preparation.'],
            ['name' => 'Events & Activations', 'code' => 'EVENTS_ACTIVATIONS', 'description' => 'Events, activations, branding, and on-ground execution.'],
            ['name' => 'Media Zone', 'code' => 'MEDIA_ZONE', 'description' => 'Media Zone services and media production support.'],
            ['name' => 'QA Team', 'code' => 'QA', 'description' => 'Quality assurance, review, and output checking.'],
            ['name' => 'Archive Team', 'code' => 'ARCHIVE', 'description' => 'Archive, storage, file organization, and final asset control.'],
            ['name' => 'Customer Support', 'code' => 'CUSTOMER_SUPPORT', 'description' => 'Support tickets and customer service operations.'],
            ['name' => 'Strategy Team', 'code' => 'STRATEGY', 'description' => 'Campaign strategy, brand narrative, planning, and positioning.'],
            ['name' => 'Operations Management', 'code' => 'OPERATIONS', 'description' => 'Operations leadership and delivery governance.'],
            ['name' => 'Administration', 'code' => 'ADMINISTRATION', 'description' => 'Executive administration, HR coordination, and company management.'],
        ];

        foreach ($teams as $team) {
            Team::updateOrCreate(
                ['code' => $team['code']],
                $team + ['is_active' => true]
            );
        }
    }
}
