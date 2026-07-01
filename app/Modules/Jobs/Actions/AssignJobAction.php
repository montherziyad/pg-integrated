<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\JobAssignment;
use Illuminate\Support\Facades\Auth;

class AssignJobAction
{
    public function execute(CreativeJob $job, array $data): JobAssignment
    {
        return JobAssignment::create([

            'creative_job_id' => $job->id,

            'team_id' => $data['team_id'],

            // مدير القسم
            'supervisor_id' => $data['supervisor_id'] ?? null,

            // الموظف (قد يكون فارغاً في البداية)
            'user_id' => $data['user_id'] ?? null,

            // من قام بالإسناد
            'assigned_by' => Auth::id(),

            'assigned_at' => now(),

            'estimated_hours' => $data['estimated_hours'] ?? 0,

            'status' => 'ASSIGNED',

            'notes' => $data['notes'] ?? null,

        ]);
    }
}