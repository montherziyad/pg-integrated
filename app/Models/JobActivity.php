<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobActivity extends Model
{
    protected $fillable = [
        'creative_job_id',
        'user_id',
        'activity',
        'activity_type',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'device',
        'activity_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'activity_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(CreativeJob::class, 'creative_job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}