<?php

namespace App\Modules\Jobs\Services;

use App\Models\CreativeJob;
use App\Models\WorkflowStage;
use App\Modules\Jobs\Repositories\JobRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobService
{
    protected JobRepository $repository;

    public function __construct(JobRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): CreativeJob
    {
        return DB::transaction(function () use ($data) {

            $trafficStage = WorkflowStage::where('code', 'TRAFFIC')->first();

            $jobNumber = $this->generateJobNumber();

            return $this->repository->create([
                'job_number' => $jobNumber,
                'client_id' => $data['client_id'],
                'project_id' => $data['project_id'] ?? null,
                'job_category_id' => $data['job_category_id'] ?? null,
                'current_workflow_stage_id' => $trafficStage?->id,
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
        $next = CreativeJob::count() + 1;

        return 'JOB-' .
            now()->format('Y') .
            '-' .
            str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}