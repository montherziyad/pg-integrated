<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreativeJob extends Model
{
    protected $table = 'creative_jobs';

    protected $fillable = [
        'job_number',
        'client_id',
        'project_id',
        'job_category_id',
        'job_status_id',
        'title',
        'brief',
        'priority',
        'received_at',
        'first_draft_due_at',
        'final_due_at',
        'estimated_hours',
        'created_by',
    ];
}