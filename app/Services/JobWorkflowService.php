<?php

namespace App\Services;

use App\Models\CreativeJob;
use App\Models\JobApproval;
use App\Models\JobActivity;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Support\Collection;

class JobWorkflowService
{
    /**
     * Move a job to the next stage
     */
    public function moveJobToNextStage(CreativeJob $job, ?string $notes = null, ?User $movedBy = null): bool
    {
        $currentStage = $job->currentWorkflowStage;
        if (!$currentStage) {
            return false;
        }

        // Get next stage by sort order
        $nextStage = WorkflowStage::where('sort_order', '>', $currentStage->sort_order)
            ->orderBy('sort_order')
            ->first();

        if (!$nextStage) {
            return false;
        }

        return $this->moveJobToStage($job, $nextStage, $notes, $movedBy);
    }

    /**
     * Move job to specific stage
     */
    public function moveJobToStage(CreativeJob $job, WorkflowStage $stage, ?string $notes = null, ?User $movedBy = null): bool
    {
        $movedBy = $movedBy ?? auth()->user();

        // Check if all required approvals are completed
        if (!$this->canMoveToStage($job, $stage)) {
            return false;
        }

        $oldStage = $job->currentWorkflowStage;
        $job->current_workflow_stage_id = $stage->id;
        $job->save();

        // Record activity
        $this->logActivity(
            $job,
            'stage_changed',
            "Job moved from {$oldStage->name} إلى {$stage->name}",
            $notes,
            $movedBy
        );

        return true;
    }

    /**
     * Check if job can move to stage
     */
    public function canMoveToStage(CreativeJob $job, WorkflowStage $stage): bool
    {
        if ($stage->is_start) {
            return false;
        }

        // Check pending approvals for current stage
        $pendingApprovalsCount = $job->approvals()
            ->where('workflow_stage_id', $job->current_workflow_stage_id)
            ->where('status', 'pending')
            ->where('is_required', true)
            ->count();

        return $pendingApprovalsCount === 0;
    }

    /**
     * Create approval requirement for a job at a specific stage
     */
    public function createApprovalRequirement(
        CreativeJob $job,
        WorkflowStage $stage,
        string $role,
        User $approver,
        int $order = 0,
        bool $isRequired = true
    ): JobApproval {
        return JobApproval::create([
            'creative_job_id' => $job->id,
            'workflow_stage_id' => $stage->id,
            'assigned_to_user_id' => $approver->id,
            'role' => $role,
            'approval_order' => $order,
            'is_required' => $isRequired,
            'status' => 'pending',
        ]);
    }

    /**
     * Approve a job approval request
     */
    public function approveJob(JobApproval $approval, User $approver, ?string $comments = null): bool
    {
        $approval->status = 'approved';
        $approval->approved_by_user_id = $approver->id;
        $approval->approved_at = now();
        $approval->comments = $comments;
        $approval->save();

        $this->logActivity(
            $approval->job,
            'approval_granted',
            "Job approved by {$approver->name} ({$approval->role})",
            $comments,
            $approver
        );

        // Check if all approvals are done
        if ($this->areAllApprovalsCompleted($approval->job)) {
            $this->logActivity(
                $approval->job,
                'all_approvals_completed',
                'All required approvals obtained',
                null,
                $approver
            );
        }

        return true;
    }

    /**
     * Reject a job approval request
     */
    public function rejectJob(JobApproval $approval, User $rejector, string $comments): bool
    {
        $approval->status = 'rejected';
        $approval->approved_by_user_id = $rejector->id;
        $approval->rejected_at = now();
        $approval->comments = $comments;
        $approval->save();

        $this->logActivity(
            $approval->job,
            'approval_rejected',
            "تم رفض المهمة من قبل {$rejector->name} ({$approval->role})",
            $comments,
            $rejector
        );

        return true;
    }

    /**
     * Check if all required approvals are completed
     */
    public function areAllApprovalsCompleted(CreativeJob $job): bool
    {
        $requiredApprovals = $job->approvals()
            ->where('workflow_stage_id', $job->current_workflow_stage_id)
            ->where('is_required', true)
            ->count();

        $approvedCount = $job->approvals()
            ->where('workflow_stage_id', $job->current_workflow_stage_id)
            ->where('status', 'approved')
            ->count();

        return $requiredApprovals === $approvedCount && $requiredApprovals > 0;
    }

    /**
     * Get pending approvals for a user
     */
    public function getPendingApprovalsForUser(User $user): Collection
    {
        return JobApproval::where('assigned_to_user_id', $user->id)
            ->where('status', 'pending')
            ->with('job', 'workflowStage')
            ->get();
    }

    /**
     * Log job activity
     */
    public function logActivity(
        CreativeJob $job,
        string $type,
        string $description,
        ?string $details = null,
        ?User $user = null
    ): JobActivity {
        return JobActivity::create([
            'creative_job_id' => $job->id,
            'type' => $type,
            'description' => $description,
            'details' => $details,
            'user_id' => $user?->id ?? auth()->id(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get workflow progression for a job
     */
    public function getWorkflowProgression(CreativeJob $job): array
    {
        $allStages = WorkflowStage::orderBy('sort_order')->get();
        $currentStageOrder = $job->currentWorkflowStage?->sort_order ?? 0;

        return $allStages->map(function ($stage) use ($currentStageOrder) {
            return [
                'stage' => $stage,
                'status' => $stage->sort_order < $currentStageOrder ? 'completed' :
                           ($stage->sort_order === $currentStageOrder ? 'current' : 'pending'),
            ];
        })->toArray();
    }

    /**
     * Create default approvals for initial brief stage
     */
    public function createInitialApprovals(CreativeJob $job, WorkflowStage $briefStage): void
    {
        // Approval 1: Traffic Manager (Required)
        if ($job->traffic_manager_id) {
            $this->createApprovalRequirement(
                $job,
                $briefStage,
                'traffic_manager',
                User::find($job->traffic_manager_id),
                1,
                true
            );
        }

        // Approval 2: Client Service (Required)
        if ($job->responsible_user_id) {
            $this->createApprovalRequirement(
                $job,
                $briefStage,
                'client_service',
                User::find($job->responsible_user_id),
                2,
                true
            );
        }

        // Approval 3: Project Manager (Optional)
        if ($job->project_manager_id) {
            $this->createApprovalRequirement(
                $job,
                $briefStage,
                'project_manager',
                User::find($job->project_manager_id),
                3,
                false
            );
        }
    }
}
