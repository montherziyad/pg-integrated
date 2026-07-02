<?php

namespace App\Modules\Archive\Repositories;

use App\Models\Asset;
use App\Models\CreativeJob;

class ArchiveRepository
{
    public function jobs()
    {
        return CreativeJob::query()
            ->where('is_archived', true)
            ->with(['client', 'project', 'currentWorkflowStage'])
            ->latest('archived_at')
            ->paginate(20);
    }

    public function assets()
    {
        return Asset::query()
            ->with(['job.client', 'uploader'])
            ->whereHas('job', fn ($query) => $query->where('is_archived', true))
            ->latest()
            ->paginate(50);
    }

    public function counts(): array
    {
        return [
            'jobs' => CreativeJob::where('is_archived', true)->count(),
            'assets' => Asset::whereHas('job', fn ($query) => $query->where('is_archived', true))->count(),
        ];
    }
}
