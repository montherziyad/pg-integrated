<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTransition extends Model
{
    protected $fillable = [
        'from_stage_id',
        'to_stage_id',
        'name',
        'code',
        'requires_permission',
        'required_permission',
        'requires_comment',
        'requires_file',
        'is_active',
    ];

    public function fromStage()
    {
        return $this->belongsTo(WorkflowStage::class, 'from_stage_id');
    }

    public function toStage()
    {
        return $this->belongsTo(WorkflowStage::class, 'to_stage_id');
    }
}