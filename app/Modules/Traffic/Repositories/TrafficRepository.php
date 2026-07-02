<?php

namespace App\Modules\Traffic\Repositories;

use App\Models\WorkflowStage;

class TrafficRepository
{
    public function board()
    {
        return WorkflowStage::query()
            ->where('is_active', true)
            ->with(['jobs' => fn ($query) => $query
                ->where('is_archived', false)
                ->with(['client', 'project', 'assignments.assignee'])
                ->orderByRaw('final_due_at IS NULL')
                ->orderBy('final_due_at')])
            ->orderBy('sort_order')
            ->get();
    }
}
