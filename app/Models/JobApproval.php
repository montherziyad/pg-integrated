<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApproval extends Model
{
    protected $fillable = [
        'creative_job_id',
        'workflow_stage_id',
        'assigned_to_user_id',
        'role',
        'status',
        'comments',
        'approved_at',
        'rejected_at',
        'approval_order',
        'is_required',
        'approved_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'is_required' => 'boolean',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(CreativeJob::class, 'creative_job_id');
    }

    public function workflowStage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class, 'workflow_stage_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
