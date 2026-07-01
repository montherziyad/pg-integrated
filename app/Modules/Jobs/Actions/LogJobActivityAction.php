<?php

namespace App\Modules\Jobs\Actions;

use App\Models\JobActivity;
use App\Models\CreativeJob;
use Illuminate\Support\Facades\Auth;

class LogJobActivityAction
{
    public function execute(
        CreativeJob $job,
        string $activity,
        ?string $description = null,
        string $type = 'USER'
    ): void {
        JobActivity::create([
            'creative_job_id' => $job->id,
            'user_id' => Auth::id(),
            'activity' => $activity,
            'activity_type' => $type,
            'description' => $description,
            'activity_at' => now(),
        ]);
    }
}