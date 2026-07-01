<?php

namespace App\Modules\Jobs\Actions;

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

class DeleteJobAttachmentAction
{
    public function execute(Asset $asset): void
    {
        if (Storage::disk($asset->storage_type)->exists($asset->storage_path)) {
            Storage::disk($asset->storage_type)->delete($asset->storage_path);
        }

        $asset->delete();
    }
}