<?php

namespace App\Http\Controllers;

use App\Models\CreativeJob;
use App\Models\Asset;

class ArchiveController extends Controller
{
    /**
     * Archive Dashboard
     */
    public function index()
    {
        return view('archive.index');
    }

    /**
     * Archived Jobs
     */
    public function jobs()
    {
        $jobs = CreativeJob::where('is_archived', true)
            ->latest('archived_at')
            ->paginate(20);

        return view('archive.jobs', compact('jobs'));
    }

    /**
     * Archived Files
     */
    public function assets()
    {
        $assets = Asset::latest()
            ->paginate(50);

        return view('archive.assets', compact('assets'));
    }

    /**
     * Archive Job
     */
    public function archive(CreativeJob $job)
    {
        $job->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        return back()->with(
            'success',
            'Job archived successfully.'
        );
    }

    /**
     * Restore Archived Job
     */
    public function restore(CreativeJob $job)
    {
        $job->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);

        return back()->with(
            'success',
            'Job restored successfully.'
        );
    }
}