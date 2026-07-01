<?php

namespace App\Modules\Jobs\Services;

use App\Models\CreativeJob;
use App\Modules\Jobs\Actions\AssignJobAction;
use App\Modules\Jobs\Actions\CreateJobAction;
use App\Modules\Jobs\Actions\LogJobActivityAction;
use App\Modules\Jobs\Actions\UploadJobAttachmentsAction;
use App\Modules\Jobs\Repositories\JobRepository;

class JobService
{
    public function __construct(
        protected JobRepository $repository,
        protected CreateJobAction $createJobAction,
        protected AssignJobAction $assignJobAction,
        protected UploadJobAttachmentsAction $uploadJobAttachmentsAction,
        protected LogJobActivityAction $logJobActivityAction,
    ) {}

    public function create(array $data): CreativeJob
    {
        $job = $this->createJobAction->execute($data);

        if (!empty($data['attachments'])) {
            $this->uploadJobAttachmentsAction->execute(
                $job,
                $data['attachments']
            );
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

    public function find(int $id): ?CreativeJob
    {
        return $this->repository->find($id);
    }

    public function all()
    {
        return $this->repository->all();
    }
}