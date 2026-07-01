<?php

namespace App\Modules\Jobs\Actions;

use App\Models\Asset;
use App\Models\CreativeJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class UploadJobAttachmentsAction
{
    public function execute(CreativeJob $job, array $files = []): void
    {
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store(
                'creative-jobs/' . $job->job_number . '/briefs',
                'local'
            );

            Asset::create([
                'creative_job_id' => $job->id,
                'uploaded_by' => Auth::id(),
                'file_name' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'storage_type' => 'local',
                'storage_path' => $path,
                'version' => 1,
                'asset_stage' => 'BRIEF',
                'is_final' => false,
            ]);
        }
    }
}