<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\WorkflowStage;

class CompleteJobAction
{
    public function execute(CreativeJob $job): bool
    {
        $completedStage = WorkflowStage::where('code', 'COMPLETED')->first();

        return $job->update([
            'current_workflow_stage_id' => $completedStage?->id,
            'completion_percentage' => 100,
            'final_delivered_at' => now(),
        ]);
    }
}