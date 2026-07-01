<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobAssignment extends Model
{
    protected $fillable = [
        'creative_job_id',
        'team_id',
        'supervisor_id',
        'user_id',
        'assigned_by',
        'assigned_at',
        'estimated_hours',
        'actual_hours',
        'started_at',
        'completed_at',
        'status',
        'notes',
    ];

    public function job()
    {
        return $this->belongsTo(CreativeJob::class, 'creative_job_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}