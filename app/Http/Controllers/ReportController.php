<?php

namespace App\Http\Controllers;

use App\Modules\Reports\Services\ReportService;

class ReportController extends Controller
{
    public function __construct(protected ReportService $service) {}

    public function index()
    {
        return view('reports.index', $this->service->summary());
    }
}
