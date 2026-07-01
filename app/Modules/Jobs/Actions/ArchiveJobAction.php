<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\WorkflowStage;

class ArchiveJobAction
{
    public function execute(CreativeJob $job): bool
    {
        $archiveStage = WorkflowStage::where('code', 'ARCHIVE')->first();

        return $job->update([
            'current_workflow_stage_id' => $archiveStage?->id,
            'is_archived' => true,
            'archived_at' => now(),
        ]);
    }
}