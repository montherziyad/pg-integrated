<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\WorkflowStage;

class ReopenJobAction
{
    public function execute(CreativeJob $job): bool
    {
        $revisionStage = WorkflowStage::where('code', 'REVISION')->first();

        return $job->update([
            'current_workflow_stage_id' => $revisionStage?->id,
            'completion_percentage' => 80,
            'reopened_count' => ($job->reopened_count ?? 0) + 1,
        ]);
    }
}