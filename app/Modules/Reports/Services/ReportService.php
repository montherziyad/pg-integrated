<?php

namespace App\Modules\Reports\Services;

use App\Modules\Reports\Repositories\ReportRepository;

class ReportService
{
    public function __construct(protected ReportRepository $repository) {}

    public function summary(): array
    {
        return $this->repository->summary();
    }
}
