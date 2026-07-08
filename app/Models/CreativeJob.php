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
        'responsible_user_id',
        'title',
        'brief',
        'priority',
        'received_at',
        'first_draft_due_at',
        'final_due_at',
        'production_due_at',
        'production_due_confirmed_by',
        'production_due_confirmed_at',
        'production_due_notes',
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
        'delivery_review_status',
        'delivery_reviewed_by',
        'delivery_reviewed_at',
        'delivery_published_at',
        'employee_handover_status',
        'employee_handover_link',
        'employee_handover_notes',
        'employee_handover_submitted_by',
        'employee_handover_submitted_at',
        'is_archived',
        'archived_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
            'first_draft_due_at' => 'datetime',
            'final_due_at' => 'datetime',
            'production_due_at' => 'datetime',
            'production_due_confirmed_at' => 'datetime',
            'first_draft_sent_at' => 'datetime',
            'final_delivered_at' => 'datetime',
            'delivery_reviewed_at' => 'datetime',
            'delivery_published_at' => 'datetime',
            'employee_handover_submitted_at' => 'datetime',
            'archived_at' => 'datetime',
            'is_archived' => 'boolean',
        ];
    }

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

    public function assignedDesigners()
    {
        return $this->assignments
            ->pluck('assignee')
            ->filter()
            ->unique('id')
            ->values();
    }

    public function assignmentLeads()
    {
        return $this->assignments
            ->pluck('supervisor')
            ->filter()
            ->unique('id')
            ->values();
    }

    public function assignedDesignerNames(): string
    {
        return $this->assignedDesigners()->pluck('name')->implode(', ');
    }

    public function assignmentLeadNames(): string
    {
        return $this->assignmentLeads()->pluck('name')->implode(', ');
    }

    public function activities()
    {
        return $this->hasMany(JobActivity::class, 'creative_job_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'creative_job_id');
    }

    public function approvals()
    {
        return $this->hasMany(JobApproval::class, 'creative_job_id');
    }

    public function pendingApprovals()
    {
        return $this->approvals()->where('status', 'pending');
    }

    public function revisions()
    {
        return $this->hasMany(Revision::class, 'creative_job_id');
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function deliveryReviewer()
    {
        return $this->belongsTo(User::class, 'delivery_reviewed_by');
    }

    public function employeeHandoverSubmitter()
    {
        return $this->belongsTo(User::class, 'employee_handover_submitted_by');
    }

    public function productionDueConfirmer()
    {
        return $this->belongsTo(User::class, 'production_due_confirmed_by');
    }

    public function scopeVisibleToUser($query, ?User $user)
    {
        if (! $user || app()->environment('testing') && ! $user->role) {
            return $query;
        }

        $canSeeAll = $user->canAccessScreen('traffic_board')
            || $user->canAccessScreen('email_intake')
            || $user->canAccessScreen('deliveries')
            || $user->canAccessScreen('clients');

        if ($canSeeAll) {
            return $query;
        }

        return $query->where(function ($query) use ($user): void {
            $query->where('responsible_user_id', $user->id)
                ->orWhereHas('assignments', fn ($assignment) => $assignment
                    ->where('user_id', $user->id)
                    ->orWhere('supervisor_id', $user->id));
        });
    }

    public function isAssignedTo(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return (int) $this->responsible_user_id === (int) $user->id
            || $this->assignments->contains(fn ($assignment) => (int) $assignment->user_id === (int) $user->id || (int) $assignment->supervisor_id === (int) $user->id);
    }

    // Workflow Management Methods
    public function canTransitionTo(WorkflowStage $stage, ?User $user = null): bool
    {
        // Check if current stage allows transition to this stage
        $currentStage = $this->currentWorkflowStage;
        if (!$currentStage) {
            return $stage->is_start;
        }

        // Check if all required approvals for current stage are completed
        if ($this->hasRequiredApprovalsForCurrentStage() === false) {
            return false;
        }

        return true;
    }

    public function hasRequiredApprovalsForCurrentStage(): bool
    {
        $requiredApprovals = $this->approvals()
            ->where('workflow_stage_id', $this->current_workflow_stage_id)
            ->where('is_required', true)
            ->count();

        $approvedCount = $this->approvals()
            ->where('workflow_stage_id', $this->current_workflow_stage_id)
            ->where('status', 'approved')
            ->count();

        return $requiredApprovals === $approvedCount;
    }

    public function getPendingApprovalsForRole(string $role)
    {
        return $this->approvals()
            ->where('workflow_stage_id', $this->current_workflow_stage_id)
            ->where('role', $role)
            ->where('status', 'pending')
            ->get();
    }

    public function moveToStage(WorkflowStage $stage, ?string $notes = null): bool
    {
        if (!$this->canTransitionTo($stage)) {
            return false;
        }

        $this->current_workflow_stage_id = $stage->id;
        $this->save();

        // Record stage history
        JobStageHistory::create([
            'creative_job_id' => $this->id,
            'from_stage_id' => $this->currentWorkflowStage->id,
            'to_stage_id' => $stage->id,
            'notes' => $notes,
            'moved_by' => auth()->id(),
        ]);

        return true;
    }

    public function getCompletionPercentage(): int
    {
        $totalStages = WorkflowStage::where('is_start', false)
            ->where('is_end', false)
            ->count();

        $currentOrder = $this->currentWorkflowStage?->sort_order ?? 0;
        $percentage = round(($currentOrder / $totalStages) * 100);

        return min(100, max(0, $percentage));
    }

    public function getAllAssignedUsers()
    {
        return $this->assignments()
            ->with('assignee')
            ->get()
            ->map(fn ($assignment) => $assignment->assignee)
            ->unique('id');
    }

    public function hasApprovalFromRole(string $role, string $status = 'approved'): bool
    {
        return $this->approvals()
            ->where('role', $role)
            ->where('status', $status)
            ->exists();
    }

    public function requiresApprovalFrom(string $role): bool
    {
        return $this->approvals()
            ->where('role', $role)
            ->where('is_required', true)
            ->where('status', 'pending')
            ->exists();
    }
}
