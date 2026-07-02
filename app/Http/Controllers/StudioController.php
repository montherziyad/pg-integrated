<?php

namespace App\Http\Controllers;

use App\Modules\Workload\Services\WorkloadService;

class StudioController extends Controller
{
    public function __construct(protected WorkloadService $service) {}

    public function index()
    {
        return view('workload.index', ['users' => $this->service->users()]);
    }
}
