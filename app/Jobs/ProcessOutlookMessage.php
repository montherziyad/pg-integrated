<?php

namespace App\Jobs;

use App\Actions\ImportOutlookEmailAction;
use App\Modules\EmailIntakes\Services\MicrosoftGraphService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessOutlookMessage implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public string $messageId) {}

    public function handle(MicrosoftGraphService $graph, ImportOutlookEmailAction $importer): void
    {
        $importer->execute($graph->fetchMessage($this->messageId));
    }
}
