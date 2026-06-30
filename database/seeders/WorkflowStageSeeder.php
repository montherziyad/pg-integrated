<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkflowStage;

class WorkflowStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkflowStage::insert([

            [
                'name' => 'Email Intake',
                'code' => 'EMAIL',
                'description' => 'Incoming email from Outlook',
                'sort_order' => 1,
                'color' => '#3B82F6',
                'icon' => 'mail',
                'is_start' => true,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Traffic',
                'code' => 'TRAFFIC',
                'description' => 'Traffic Department',
                'sort_order' => 2,
                'color' => '#0EA5E9',
                'icon' => 'route',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Content',
                'code' => 'CONTENT',
                'description' => 'Content Writing',
                'sort_order' => 3,
                'color' => '#6366F1',
                'icon' => 'pen',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Design',
                'code' => 'DESIGN',
                'description' => 'Graphic Design',
                'sort_order' => 4,
                'color' => '#8B5CF6',
                'icon' => 'palette',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Motion',
                'code' => 'MOTION',
                'description' => 'Motion Graphics',
                'sort_order' => 5,
                'color' => '#EC4899',
                'icon' => 'film',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'QA Review',
                'code' => 'QA',
                'description' => 'Quality Assurance',
                'sort_order' => 6,
                'color' => '#F59E0B',
                'icon' => 'shield-check',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => true,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Client Review',
                'code' => 'CLIENT',
                'description' => 'Waiting Client Approval',
                'sort_order' => 7,
                'color' => '#F97316',
                'icon' => 'user-check',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => true,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Revision',
                'code' => 'REVISION',
                'description' => 'Client Revision',
                'sort_order' => 8,
                'color' => '#EF4444',
                'icon' => 'refresh-cw',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Completed',
                'code' => 'COMPLETED',
                'description' => 'Completed Successfully',
                'sort_order' => 9,
                'color' => '#22C55E',
                'icon' => 'check-circle',
                'is_start' => false,
                'is_end' => false,
                'requires_approval' => false,
                'allow_file_upload' => true,
                'allow_comments' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Archive',
                'code' => 'ARCHIVE',
                'description' => 'Archived Job',
                'sort_order' => 10,
                'color' => '#64748B',
                'icon' => 'archive',
                'is_start' => false,
                'is_end' => true,
                'requires_approval' => false,
                'allow_file_upload' => false,
                'allow_comments' => false,
                'is_active' => true,
            ],

        ]);
    }
}