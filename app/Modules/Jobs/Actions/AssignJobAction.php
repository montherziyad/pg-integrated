<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\JobAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AssignJobAction
{
    public function execute(CreativeJob $job, array $data): Collection
    {
        $userIds = collect($data['user_ids'] ?? [])
            ->push($data['user_id'] ?? null)
            ->filter()
            ->unique()
            ->values();

        $supervisorIds = collect($data['supervisor_ids'] ?? [])
            ->push($data['supervisor_id'] ?? null)
            ->filter()
            ->unique()
            ->values();

        $assignments = collect();
        $primarySupervisorId = $supervisorIds->first();

        // Only create assignments when there is at least a user or supervisor
        if ($userIds->isNotEmpty()) {
            foreach ($userIds as $userId) {
                $assignments->push($this->createAssignment($job, $data, $userId, $primarySupervisorId));
            }

            // Any remaining supervisors become separate supervisor-only assignments
            $supervisorIds
                ->skip($primarySupervisorId ? 1 : 0)
                ->each(fn ($supervisorId) => $assignments->push($this->createAssignment($job, $data, null, $supervisorId)));
        } elseif ($supervisorIds->isNotEmpty()) {
            foreach ($supervisorIds as $supervisorId) {
                $assignments->push($this->createAssignment($job, $data, null, $supervisorId));
            }
        } else {
            // No assignees provided — do not create empty assignments; return empty collection
            return $assignments;
        }

        // Remove any accidental orphan assignments (both user_id and supervisor_id null) for this job
        \App\Models\JobAssignment::query()
            ->where('creative_job_id', $job->id)
            ->whereNull('user_id')
            ->whereNull('supervisor_id')
            ->delete();

        // Ensure job has a workflow stage and is not archived so it appears on Traffic Board
        if (empty($job->current_workflow_stage_id)) {
            $startStage = \App\Models\WorkflowStage::where('code', 'email_received')->first();
            if ($startStage) {
                $job->current_workflow_stage_id = $startStage->id;
            }
        }

        if ($job->is_archived) {
            $job->is_archived = false;
        }

        $job->save();

        return $assignments->values();
    }

    protected function createAssignment(CreativeJob $job, array $data, ?int $userId, ?int $supervisorId): JobAssignment
    {
        return JobAssignment::create([
            'creative_job_id' => $job->id,
            'team_id' => $data['team_id'],
            'supervisor_id' => $supervisorId,
            'user_id' => $userId,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
            'estimated_hours' => $data['estimated_hours'] ?? 0,
            'status' => 'ASSIGNED',
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
