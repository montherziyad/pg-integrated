<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\JobStageHistory;
use App\Models\WorkflowStage;
use Illuminate\Support\Facades\Auth;

class ChangeWorkflowStageAction
{
    public function execute(CreativeJob $job, WorkflowStage $stage, ?string $notes = null): bool
    {
        JobStageHistory::where('creative_job_id', $job->id)
            ->where('is_current', true)
            ->update([
                'left_at' => now(),
                'is_current' => false,
            ]);

        JobStageHistory::create([
            'creative_job_id' => $job->id,
            'workflow_stage_id' => $stage->id,
            'entered_by' => Auth::id(),
            'entered_at' => now(),
            'is_current' => true,
            'notes' => $notes,
        ]);

        return $job->update([
            'current_workflow_stage_id' => $stage->id,
        ]);
    }
}