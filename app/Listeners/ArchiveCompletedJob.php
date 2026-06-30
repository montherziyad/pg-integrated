<?php

namespace App\Listeners;

use App\Events\JobArchived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ArchiveCompletedJob
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JobArchived $event): void
    {
        //
    }
}
