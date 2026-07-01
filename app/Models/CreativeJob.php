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
        'current_workflow_stage_id',
        'traffic_manager_id',
        'project_manager_id',
        'title',
        'brief',
        'priority',
        'received_at',
        'first_draft_due_at',
        'final_due_at',
        'first_draft_sent_at',
        'final_delivered_at',
        'estimated_hours',
        'actual_hours',
        'revision_count',
        'reopened_count',
        'completion_percentage',
        'internal_notes',
        'client_notes',
        'nas_folder_path',
        'dropbox_folder_path',
        'final_delivery_path',
        'is_archived',
        'archived_at',
        'created_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function currentWorkflowStage()
    {
        return $this->belongsTo(WorkflowStage::class, 'current_workflow_stage_id');
    }

    public function assignments()
    {
        return $this->hasMany(JobAssignment::class, 'creative_job_id');
    }

    public function activities()
    {
        return $this->hasMany(JobActivity::class, 'creative_job_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'creative_job_id');
    }
}