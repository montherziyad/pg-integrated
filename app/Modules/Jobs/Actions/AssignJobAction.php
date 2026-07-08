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

        if ($userIds->isNotEmpty()) {
            foreach ($userIds as $userId) {
                $assignments->push($this->createAssignment($job, $data, $userId, $primarySupervisorId));
            }

            $supervisorIds
                ->skip($primarySupervisorId ? 1 : 0)
                ->each(fn ($supervisorId) => $assignments->push($this->createAssignment($job, $data, null, $supervisorId)));
        } elseif ($supervisorIds->isNotEmpty()) {
            foreach ($supervisorIds as $supervisorId) {
                $assignments->push($this->createAssignment($job, $data, null, $supervisorId));
            }
        } else {
            $assignments->push($this->createAssignment($job, $data, null, null));
        }

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
