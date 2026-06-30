<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::insert([
            [
                'name' => 'Traffic Team',
                'code' => 'TRAFFIC',
                'description' => 'Traffic Management Team',
                'is_active' => true,
            ],
            [
                'name' => 'Design Team',
                'code' => 'DESIGN',
                'description' => 'Graphic Design Team',
                'is_active' => true,
            ],
            [
                'name' => 'Content Team',
                'code' => 'CONTENT',
                'description' => 'Content Creation Team',
                'is_active' => true,
            ],
            [
                'name' => 'Motion Team',
                'code' => 'MOTION',
                'description' => 'Motion Graphics Team',
                'is_active' => true,
            ],
            [
                'name' => 'QA Team',
                'code' => 'QA',
                'description' => 'Quality Assurance Team',
                'is_active' => true,
            ],
            [
                'name' => 'Archive Team',
                'code' => 'ARCHIVE',
                'description' => 'Archive and Storage Team',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Service Team',
                'code' => 'CS',
                'description' => 'Customer Service Team',
                'is_active' => true,
            ],
        ]);
    }
}