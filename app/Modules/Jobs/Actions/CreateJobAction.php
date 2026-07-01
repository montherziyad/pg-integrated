<?php

namespace App\Modules\Jobs\Actions;

use App\Models\CreativeJob;
use App\Models\WorkflowStage;
use App\Modules\Jobs\Repositories\JobRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateJobAction
{
    public function __construct(
        protected JobRepository $jobs
    ) {}

    public function execute(array $data): CreativeJob
    {
        return DB::transaction(function () use ($data) {
            $stage = WorkflowStage::where('code', 'TRAFFIC')->first();

            return $this->jobs->create([
                'job_number' => $this->generateJobNumber(),
                'client_id' => $data['client_id'],
                'project_id' => $data['project_id'] ?? null,
                'job_category_id' => $data['job_category_id'] ?? null,
                'current_workflow_stage_id' => $stage?->id,
                'title' => $data['title'],
                'brief' => $data['brief'] ?? null,
                'priority' => $data['priority'],
                'received_at' => now(),
                'first_draft_due_at' => $data['first_draft_due_at'] ?? null,
                'final_due_at' => $data['final_due_at'] ?? null,
                'estimated_hours' => $data['estimated_hours'] ?? 0,
                'created_by' => Auth::id(),
            ]);
        });
    }

    protected function generateJobNumber(): string
    {
        return 'JOB-' . now()->format('Y') . '-' . str_pad(
            CreativeJob::count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}