<?php

namespace App\Modules\Jobs\Services;

use App\Models\Asset;
use App\Models\CreativeJob;
use App\Models\WorkflowStage;
use App\Modules\Jobs\Actions\ArchiveJobAction;
use App\Modules\Jobs\Actions\AssignJobAction;
use App\Modules\Jobs\Actions\ChangeWorkflowStageAction;
use App\Modules\Jobs\Actions\CompleteJobAction;
use App\Modules\Jobs\Actions\CreateJobAction;
use App\Modules\Jobs\Actions\DeleteJobAttachmentAction;
use App\Modules\Jobs\Actions\LogJobActivityAction;
use App\Modules\Jobs\Actions\ReopenJobAction;
use App\Modules\Jobs\Actions\UploadJobAttachmentsAction;
use App\Modules\Jobs\Repositories\JobRepository;

class JobService
{
    public function __construct(
        protected JobRepository $repository,
        protected CreateJobAction $createJobAction,
        protected AssignJobAction $assignJobAction,
        protected UploadJobAttachmentsAction $uploadJobAttachmentsAction,
        protected DeleteJobAttachmentAction $deleteJobAttachmentAction,
        protected LogJobActivityAction $logJobActivityAction,
        protected CompleteJobAction $completeJobAction,
        protected ReopenJobAction $reopenJobAction,
        protected ArchiveJobAction $archiveJobAction,
        protected ChangeWorkflowStageAction $changeWorkflowStageAction,
    ) {}

    public function create(array $data): CreativeJob
    {
        $job = $this->createJobAction->execute($data);

        if (! empty($data['attachments'])) {
            $this->uploadJobAttachmentsAction->execute($job, $data['attachments']);
        }

        $this->logJobActivityAction->execute(
            $job,
            'JOB_CREATED',
            'Creative Job created successfully.'
        );

        return $job;
    }

    public function assign(CreativeJob $job, array $data)
    {
        $assignment = $this->assignJobAction->execute($job, $data);

        $this->logJobActivityAction->execute(
            $job,
            'JOB_ASSIGNED',
            'Job assigned successfully.'
        );

        return $assignment;
    }

    public function uploadAttachments(CreativeJob $job, array $files): void
    {
        $this->uploadJobAttachmentsAction->execute($job, $files);

        $this->logJobActivityAction->execute(
            $job,
            'ATTACHMENTS_UPLOADED',
            'Brief attachments uploaded successfully.'
        );
    }

    public function deleteAttachment(Asset $asset): void
    {
        $job = $asset->job;

        $this->deleteJobAttachmentAction->execute($asset);

        if ($job) {
            $this->logJobActivityAction->execute(
                $job,
                'ATTACHMENT_DELETED',
                'Attachment deleted successfully.'
            );
        }
    }

    public function complete(CreativeJob $job): bool
    {
        $result = $this->completeJobAction->execute($job);

        $this->logJobActivityAction->execute(
            $job,
            'JOB_COMPLETED',
            'Job marked as completed.'
        );

        return $result;
    }

    public function reopen(CreativeJob $job): bool
    {
        $result = $this->reopenJobAction->execute($job);

        $this->logJobActivityAction->execute(
            $job,
            'JOB_REOPENED',
            'Job reopened for revision.'
        );

        return $result;
    }

    public function archive(CreativeJob $job): bool
    {
        $result = $this->archiveJobAction->execute($job);

        $this->logJobActivityAction->execute(
            $job,
            'JOB_ARCHIVED',
            'Job archived successfully.'
        );

        return $result;
    }

    public function changeWorkflowStage(CreativeJob $job, array $data): bool
    {
        $stage = WorkflowStage::findOrFail($data['workflow_stage_id']);

        $result = $this->changeWorkflowStageAction->execute(
            $job,
            $stage,
            $data['notes'] ?? null
        );

        $this->logJobActivityAction->execute(
            $job,
            'WORKFLOW_STAGE_CHANGED',
            'Workflow stage changed to '.$stage->name
        );

        return $result;
    }

    public function find(int $id): ?CreativeJob
    {
        return $this->repository->find($id);
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function update(CreativeJob $job, array $data): bool
    {
        $result = $this->repository->update($job, $data);

        $this->logJobActivityAction->execute(
            $job,
            'JOB_UPDATED',
            'Creative Job updated successfully.'
        );

        return $result;
    }
}
