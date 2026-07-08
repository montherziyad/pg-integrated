<?php

namespace App\Modules\Traffic\Repositories;

use App\Models\WorkflowStage;

class TrafficRepository
{
    public function board()
    {
        // Fetch regular stages with their jobs
        $stages = WorkflowStage::query()
            ->where('is_active', true)
            ->with(['jobs' => fn ($query) => $query
                ->where('is_archived', false)
                ->with(['client', 'project', 'assignments.assignee'])
                ->orderByRaw('final_due_at IS NULL')
                ->orderBy('final_due_at')])
            ->orderBy('sort_order')
            ->get();

        // Also include any jobs that currently have no stage (unstaged)
        $unstagedJobs = \App\Models\CreativeJob::query()
            ->whereNull('current_workflow_stage_id')
            ->where('is_archived', false)
            ->with(['client', 'project', 'assignments.assignee'])
            ->orderByRaw('final_due_at IS NULL')
            ->orderBy('final_due_at')
            ->get();

        if ($unstagedJobs->isNotEmpty()) {
            $virtual = new \Illuminate\Support\Collection([ (object) [
                'id' => 0,
                'code' => 'unstaged',
                'name' => 'Unstaged / No Stage',
                'color' => '#9CA3AF',
                'jobs' => $unstagedJobs,
            ]]);

            // Prepend virtual stage so unstaged jobs appear first
            return $virtual->merge($stages);
        }

        return $stages;
    }
}
