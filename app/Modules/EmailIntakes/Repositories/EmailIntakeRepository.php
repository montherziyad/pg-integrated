<?php

namespace App\Modules\EmailIntakes\Repositories;

use App\Models\EmailIntake;

class EmailIntakeRepository
{
    public function pending()
    {
        return EmailIntake::query()
            ->withCount('attachments')
            ->with('reviewer')
            ->latest('received_at')
            ->paginate(25);
    }

    public function find(int $id): ?EmailIntake
    {
        return EmailIntake::with(['attachments', 'validations', 'reviewer', 'job'])->find($id);
    }

    public function counts(): array
    {
        return [
            'new' => EmailIntake::where('status', 'NEW')->count(),
            'valid' => EmailIntake::where('status', 'NEW')->where('validation_passed', true)->count(),
            'rejected' => EmailIntake::where('status', 'IGNORED')->count(),
            'converted' => EmailIntake::where('status', 'CONVERTED_TO_JOB')->count(),
            'failed' => EmailIntake::where('status', 'FAILED')->count(),
        ];
    }
}
