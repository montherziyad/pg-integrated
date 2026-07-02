<?php

namespace App\Modules\Workload\Services;

use App\Modules\Workload\Repositories\WorkloadRepository;

class WorkloadService
{
    public function __construct(protected WorkloadRepository $repository) {}

    public function users()
    {
        return $this->repository->users();
    }
}
