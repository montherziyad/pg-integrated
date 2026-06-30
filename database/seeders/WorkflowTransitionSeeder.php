<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkflowTransition;
use App\Models\WorkflowStage;

class WorkflowTransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = WorkflowStage::pluck('id', 'code');

        WorkflowTransition::insert([

            [
                'from_stage_id' => $stages['EMAIL'],
                'to_stage_id' => $stages['TRAFFIC'],
                'name' => 'Email → Traffic',
                'code' => 'EMAIL_TRAFFIC',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['TRAFFIC'],
                'to_stage_id' => $stages['CONTENT'],
                'name' => 'Traffic → Content',
                'code' => 'TRAFFIC_CONTENT',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['TRAFFIC'],
                'to_stage_id' => $stages['DESIGN'],
                'name' => 'Traffic → Design',
                'code' => 'TRAFFIC_DESIGN',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['TRAFFIC'],
                'to_stage_id' => $stages['MOTION'],
                'name' => 'Traffic → Motion',
                'code' => 'TRAFFIC_MOTION',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['CONTENT'],
                'to_stage_id' => $stages['DESIGN'],
                'name' => 'Content → Design',
                'code' => 'CONTENT_DESIGN',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['DESIGN'],
                'to_stage_id' => $stages['QA'],
                'name' => 'Design → QA',
                'code' => 'DESIGN_QA',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => true,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['MOTION'],
                'to_stage_id' => $stages['QA'],
                'name' => 'Motion → QA',
                'code' => 'MOTION_QA',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => true,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['QA'],
                'to_stage_id' => $stages['CLIENT'],
                'name' => 'QA → Client Review',
                'code' => 'QA_CLIENT',
                'requires_permission' => true,
                'required_permission' => 'approve_design',
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['CLIENT'],
                'to_stage_id' => $stages['REVISION'],
                'name' => 'Client → Revision',
                'code' => 'CLIENT_REVISION',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => true,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['REVISION'],
                'to_stage_id' => $stages['DESIGN'],
                'name' => 'Revision → Design',
                'code' => 'REVISION_DESIGN',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => true,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['CLIENT'],
                'to_stage_id' => $stages['COMPLETED'],
                'name' => 'Client → Completed',
                'code' => 'CLIENT_COMPLETED',
                'requires_permission' => true,
                'required_permission' => 'complete_job',
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

            [
                'from_stage_id' => $stages['COMPLETED'],
                'to_stage_id' => $stages['ARCHIVE'],
                'name' => 'Completed → Archive',
                'code' => 'COMPLETED_ARCHIVE',
                'requires_permission' => false,
                'required_permission' => null,
                'requires_comment' => false,
                'requires_file' => false,
                'is_active' => true,
            ],

        ]);
    }
}