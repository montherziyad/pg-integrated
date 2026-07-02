<?php

namespace App\Modules\EmailIntakes\Services;

use App\Models\Asset;
use App\Models\CreativeJob;
use App\Models\EmailIntake;
use App\Models\WorkflowStage;
use App\Modules\EmailIntakes\Repositories\EmailIntakeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmailIntakeService
{
    public function __construct(protected EmailIntakeRepository $repository) {}

    public function all()
    {
        return $this->repository->pending();
    }

    public function find(int $id): ?EmailIntake
    {
        return $this->repository->find($id);
    }

    public function counts(): array
    {
        return $this->repository->counts();
    }

    public function accept(EmailIntake $intake, array $data): CreativeJob
    {
        if (! $intake->validation_passed) {
            throw ValidationException::withMessages(['intake' => 'This email has failed one or more intake rules.']);
        }

        if ($intake->status !== 'NEW') {
            throw ValidationException::withMessages(['intake' => 'This email has already been reviewed.']);
        }

        return DB::transaction(function () use ($intake, $data) {
            $job = CreativeJob::where('job_number', $intake->extracted_job_number)->first();

            if (! $job) {
                $stage = WorkflowStage::where('code', 'TRAFFIC')->first();
                $job = CreativeJob::create([
                    'job_number' => $intake->extracted_job_number,
                    'client_id' => $data['client_id'],
                    'project_id' => $data['project_id'] ?? null,
                    'job_category_id' => $data['job_category_id'] ?? null,
                    'current_workflow_stage_id' => $stage?->id,
                    'traffic_manager_id' => Auth::id(),
                    'title' => $data['title'] ?? $intake->subject,
                    'brief' => $intake->body,
                    'priority' => $data['priority'],
                    'received_at' => $intake->received_at ?? now(),
                    'first_draft_due_at' => $data['first_draft_due_at'] ?? null,
                    'final_due_at' => $data['final_due_at'] ?? null,
                    'estimated_hours' => $data['estimated_hours'] ?? 0,
                    'created_by' => Auth::id(),
                ]);
            }

            $intake->attachments()->where('is_brief', true)->get()->each(function ($attachment) use ($job) {
                Asset::firstOrCreate(
                    ['creative_job_id' => $job->id, 'storage_path' => $attachment->storage_path],
                    [
                        'uploaded_by' => Auth::id(),
                        'file_name' => basename((string) $attachment->storage_path),
                        'original_name' => $attachment->name,
                        'mime_type' => $attachment->content_type,
                        'file_size' => $attachment->size,
                        'storage_type' => $attachment->storage_disk,
                        'asset_stage' => 'BRIEF',
                    ]
                );
            });

            $intake->update([
                'status' => 'CONVERTED_TO_JOB',
                'creative_job_id' => $job->id,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'accepted_at' => now(),
                'rejection_reason' => null,
            ]);

            return $job;
        });
    }

    public function reject(EmailIntake $intake, string $reason): void
    {
        if ($intake->status !== 'NEW') {
            throw ValidationException::withMessages(['intake' => 'This email has already been reviewed.']);
        }

        $intake->update([
            'status' => 'IGNORED',
            'rejection_reason' => $reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejected_at' => now(),
        ]);
    }
}
