<?php

namespace App\Modules\Archive\Services;

use App\Models\CreativeJob;
use App\Modules\Archive\Repositories\ArchiveRepository;

class ArchiveService
{
    public function __construct(protected ArchiveRepository $repository) {}

    public function counts(): array
    {
        return $this->repository->counts();
    }

    public function jobs()
    {
        return $this->repository->jobs();
    }

    public function assets()
    {
        return $this->repository->assets();
    }

    public function archive(CreativeJob $job): bool
    {
        return $job->update(['is_archived' => true, 'archived_at' => now()]);
    }

    public function restore(CreativeJob $job): bool
    {
        return $job->update(['is_archived' => false, 'archived_at' => null]);
    }
}
