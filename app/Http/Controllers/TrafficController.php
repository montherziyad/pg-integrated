<?php

namespace App\Http\Controllers;

use App\Modules\Traffic\Services\TrafficService;

class TrafficController extends Controller
{
    public function __construct(protected TrafficService $service) {}

    public function index()
    {
        return view('traffic.index', ['stages' => $this->service->board()]);
    }
}
