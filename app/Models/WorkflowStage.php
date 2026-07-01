<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStage extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'sort_order',
        'color',
        'icon',
        'is_start',
        'is_end',
        'requires_approval',
        'allow_file_upload',
        'allow_comments',
        'is_active',
    ];

    public function jobs()
    {
        return $this->hasMany(
            CreativeJob::class,
            'current_workflow_stage_id'
        );
    }
}