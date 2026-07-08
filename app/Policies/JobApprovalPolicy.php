<?php

namespace App\Policies;

use App\Models\JobApproval;
use App\Models\User;

class JobApprovalPolicy
{
    /**
     * Can the user view this approval?
     */
    public function view(User $user, JobApproval $approval): bool
    {
        // User can view if:
        // 1. They are the assigned approver
        // 2. They are an admin
        // 3. They have access to the job
        return $user->id === $approval->assigned_to_user_id
            || $user->canAccessScreen('traffic_board')
            || $user->canAccessScreen('deliveries');
    }

    /**
     * Can the user approve this request?
     */
    public function approve(User $user, JobApproval $approval): bool
    {
        return $user->id === $approval->assigned_to_user_id
            && $approval->isPending()
            && $user->hasRole($approval->role);
    }

    /**
     * Can the user reject this request?
     */
    public function reject(User $user, JobApproval $approval): bool
    {
        return $user->id === $approval->assigned_to_user_id
            && $approval->isPending()
            && $user->hasRole($approval->role);
    }
}
