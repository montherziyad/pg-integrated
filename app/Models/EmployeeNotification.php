<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeNotification extends Model
{
    protected $fillable = [
        'user_id',
        'creative_job_id',
        'type',
        'title',
        'body',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(CreativeJob::class, 'creative_job_id');
    }
}
