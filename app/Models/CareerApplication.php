<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerApplication extends Model
{
    protected $fillable = [
        'career_job_id',
        'full_name',
        'email',
        'phone',
        'current_title',
        'portfolio_url',
        'linkedin_url',
        'cv_path',
        'message',
        'status',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(CareerJob::class, 'career_job_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
