<?php

namespace App\Http\Controllers;

use App\Models\CreativeJob;
use App\Modules\Archive\Services\ArchiveService;

class ArchiveController extends Controller
{
    public function __construct(protected ArchiveService $service) {}

    public function index()
    {
        return view('archive.index', ['counts' => $this->service->counts(), 'jobs' => $this->service->jobs()]);
    }

    public function assets()
    {
        return view('archive.assets', ['assets' => $this->service->assets()]);
    }

    public function archive(CreativeJob $job)
    {
        $this->service->archive($job);

        return back()->with('success', 'Job archived successfully.');
    }

    public function restore(CreativeJob $job)
    {
        $this->service->restore($job);

        return back()->with('success', 'Job restored successfully.');
    }
}
