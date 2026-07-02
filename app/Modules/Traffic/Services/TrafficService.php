<?php

namespace App\Modules\Traffic\Services;

use App\Modules\Traffic\Repositories\TrafficRepository;

class TrafficService
{
    public function __construct(protected TrafficRepository $repository) {}

    public function board()
    {
        return $this->repository->board();
    }
}
