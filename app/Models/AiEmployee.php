<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiEmployee extends Model
{
    protected $fillable = [
        'name',
        'code',
        'job_title',
        'department',
        'description',
        'status',
        'approval_required',
        'capabilities',
        'guardrails',
        'last_run_at',
    ];

    protected function casts(): array
    {
        return [
            'approval_required' => 'boolean',
            'capabilities' => 'array',
            'guardrails' => 'array',
            'last_run_at' => 'datetime',
        ];
    }
}
