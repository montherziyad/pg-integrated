# Technical Documentation - Workflow Management System

## Overview

A comprehensive workflow management system for creative advertising projects with:
- **13 workflow stages** from email intake to final delivery
- **Advanced approval system** with role-based authorization
- **Complete audit trail** for all activities and approvals
- **Flexible approval chains** that can be customized per job

---

## Architecture

### Data Flow Diagram

```
CreativeJob (incoming brief)
    ↓
WorkflowStage (13 predefined stages)
    ↓
JobApproval (approval requirements)
    ↓
JobActivity (audit trail)
    ↓
Completion & Delivery
```

---

## Database Schema

### `job_approvals` Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| creative_job_id | bigint | FK to creative_jobs |
| workflow_stage_id | bigint | FK to workflow_stages |
| assigned_to_user_id | bigint | User responsible for approval |
| role | varchar | Role (traffic_manager, client_service, project_leader) |
| status | enum | pending, approved, rejected, commented |
| comments | text | Approval comments/feedback |
| approved_at | timestamp | When approved |
| rejected_at | timestamp | When rejected |
| approval_order | int | Sequence number (1, 2, 3...) |
| is_required | boolean | Required or optional approval |
| approved_by_user_id | bigint | User who gave approval |
| created_at | timestamp | Creation time |
| updated_at | timestamp | Last update time |

**Indexes:**
- Primary: `id`
- Composite: `(creative_job_id, workflow_stage_id)`
- Composite: `(status, assigned_to_user_id)` for quick lookups

### `workflow_stages` Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar | Stage name (Email Received, Meeting Scheduled...) |
| code | varchar | Unique code (email_received, meeting_scheduled...) |
| description | text | Stage description |
| sort_order | int | Display order (1-13) |
| color | varchar | Hex color for UI (#3B82F6, #6366F1...) |
| icon | varchar | Icon name for UI |
| is_start | boolean | Marks beginning stage |
| is_end | boolean | Marks ending stage |
| requires_approval | boolean | Stage needs approval |
| allow_file_upload | boolean | Can upload files in this stage |
| allow_comments | boolean | Can comment in this stage |
| is_active | boolean | Stage is active/usable |

---

## 13 Workflow Stages

```
┌─ Stage 1: Email Received (email_received)
│   └─ Email from client arrives
│
├─ Stage 2: Meeting Scheduled (meeting_scheduled)
│   └─ Briefing meeting scheduled
│
├─ Stage 3: Brief Approved (brief_approved)
│   └─ Brief and team assigned
│
├─ Stage 4: Awaiting Traffic Approval ⭐ (traffic_approval_pending)
│   └─ Waiting for Traffic Manager sign-off
│
├─ Stage 5: Traffic Approved (traffic_approved)
│   └─ Traffic Manager approved
│
├─ Stage 6: In Execution (in_execution)
│   └─ Project work started
│
├─ Stage 7: Awaiting Review ⭐ (review_pending)
│   └─ Deliverables ready for review
│
├─ Stage 8: Leader Approval ⭐ (leader_approval)
│   └─ Team Leader review & approval
│
├─ Stage 9: Client Service Feedback (cs_feedback)
│   └─ Feedback from Client Service
│
├─ Stage 10: Revisions Needed (revisions_needed)
│   └─ Apply revisions & feedback
│
├─ Stage 11: Final Delivery (final_delivery)
│   └─ Final deliverables approved
│
├─ Stage 12: Delivered to Client ✓ (delivered_to_client)
│   └─ Project delivered (END STATE)
│
└─ Stage 13: Cancelled ✓ (cancelled)
    └─ Project cancelled (END STATE)

⭐ = Mandatory approval required
✓ = Final stage
```

---

## Core Classes

### Model: `JobApproval`

**Location:** `app/Models/JobApproval.php`

**Relationships:**
```php
job()              // CreativeJob - The associated project
workflowStage()    // WorkflowStage - The stage requiring approval
assignedUser()     // User - Person responsible for approval
approvedBy()       // User - Person who gave approval (if approved)
```

**Methods:**
```php
isPending()        // bool - Is approval still pending?
isApproved()       // bool - Has this been approved?
isRejected()       // bool - Has this been rejected?
```

### Model: `CreativeJob` (Enhanced)

**Location:** `app/Models/CreativeJob.php`

**New Relationships:**
```php
approvals()           // hasMany(JobApproval) - All approvals
pendingApprovals()    // hasMany(JobApproval) - Pending only
revisions()           // hasMany(Revision) - Change requests
```

**New Methods:**
```php
// Workflow management
canTransitionTo(WorkflowStage $stage): bool
hasRequiredApprovalsForCurrentStage(): bool
moveToStage(WorkflowStage $stage, ?string $notes = null): bool
getCompletionPercentage(): int

// Approval queries
getPendingApprovalsForRole(string $role): Collection
hasApprovalFromRole(string $role, string $status = 'approved'): bool
requiresApprovalFrom(string $role): bool
getAllAssignedUsers(): Collection
```

### Service: `JobWorkflowService`

**Location:** `app/Services/JobWorkflowService.php`

**Core Methods:**

**Stage Transitions:**
```php
moveJobToNextStage(CreativeJob $job, ?string $notes = null, ?User $movedBy = null): bool
moveJobToStage(CreativeJob $job, WorkflowStage $stage, ?string $notes = null, ?User $movedBy = null): bool
canMoveToStage(CreativeJob $job, WorkflowStage $stage): bool
```

**Approval Management:**
```php
createApprovalRequirement(
    CreativeJob $job,
    WorkflowStage $stage,
    string $role,
    User $approver,
    int $order = 0,
    bool $isRequired = true
): JobApproval

approveJob(JobApproval $approval, User $approver, ?string $comments = null): bool
rejectJob(JobApproval $approval, User $rejector, string $comments): bool
areAllApprovalsCompleted(CreativeJob $job): bool
```

**Queries:**
```php
getPendingApprovalsForUser(User $user): Collection
getWorkflowProgression(CreativeJob $job): array
```

**Initialization:**
```php
createInitialApprovals(CreativeJob $job, WorkflowStage $briefStage): void
logActivity(
    CreativeJob $job,
    string $type,
    string $description,
    ?string $details = null,
    ?User $user = null
): JobActivity
```

### Controller: `JobApprovalController`

**Location:** `app/Http/Controllers/JobApprovalController.php`

**Endpoints:**

| Method | Route | Handler | Description |
|--------|-------|---------|-------------|
| GET | /approvals | index() | List pending approvals |
| GET | /approvals/{approval} | show() | View approval details |
| POST | /approvals/{approval}/approve | approve() | Grant approval |
| POST | /approvals/{approval}/reject | reject() | Deny approval |
| POST | /approvals/bulk-approve | bulkApprove() | Multiple approvals |
| GET | /jobs/{job}/approvals | jobApprovals() | View job's approvals |

### Policy: `JobApprovalPolicy`

**Location:** `app/Policies/JobApprovalPolicy.php`

**Authorization Methods:**
```php
view(User $user, JobApproval $approval): bool      // Can user see this?
approve(User $user, JobApproval $approval): bool   // Can user approve?
reject(User $user, JobApproval $approval): bool    // Can user reject?
```

---

## Usage Examples

### Example 1: Create Initial Approvals

```php
use App\Services\JobWorkflowService;
use App\Models\CreativeJob;
use App\Models\WorkflowStage;

// Resolve the service
$service = app(JobWorkflowService::class);

// Get the job and stage
$job = CreativeJob::find(1);
$stage = WorkflowStage::where('code', 'brief_approved')->first();

// Create default approvals
// This creates:
// - JobApproval for Traffic Manager (required, order=1)
// - JobApproval for Client Service (required, order=2)
// - JobApproval for Project Manager (optional, order=3)
$service->createInitialApprovals($job, $stage);
```

### Example 2: Approve a Request

```php
use App\Services\JobWorkflowService;
use App\Models\JobApproval;

$service = app(JobWorkflowService::class);
$approval = JobApproval::find(1);
$user = auth()->user();

// Check authorization
if ($user->can('approve', $approval)) {
    // Grant approval
    $service->approveJob($approval, $user, 'Looks good, approved.');
    
    // System now:
    // 1. Updates approval status to 'approved'
    // 2. Records who approved and when
    // 3. Logs the activity
    // 4. Checks if all approvals are done
}
```

### Example 3: Move to Next Stage

```php
$service = app(JobWorkflowService::class);
$job = CreativeJob::find(1);

// Check if we can move
if ($service->canMoveToStage($job, $nextStage)) {
    // Move the job
    $service->moveJobToNextStage(
        $job,
        'All approvals completed, moving to execution'
    );
    
    // System now:
    // 1. Updates current_workflow_stage_id
    // 2. Saves to job_stage_histories
    // 3. Creates new approvals for this stage
    // 4. Logs the transition
}
```

### Example 4: Get Pending Approvals

```php
$service = app(JobWorkflowService::class);
$user = auth()->user();

// Get all pending approvals for this user
$pending = $service->getPendingApprovalsForUser($user);

// Filter by role
$trafficAppro vals = $pending->filter(fn($a) => $a->role === 'traffic_manager');
```

### Example 5: Check Approval Status

```php
$job = CreativeJob::find(1);

// Is approval pending for a specific role?
if ($job->requiresApprovalFrom('traffic_manager')) {
    echo "Waiting for Traffic Manager";
}

// Has approval been granted?
if ($job->hasApprovalFromRole('client_service')) {
    echo "Client Service has approved";
}

// All approvals done?
if ($job->hasRequiredApprovalsForCurrentStage()) {
    echo "Ready to move to next stage";
}

// Completion percentage
echo "Progress: " . $job->getCompletionPercentage() . "%";
```

---

## Database Migrations

### Migration: `create_job_approvals_table`

**Location:** `database/migrations/2026_07_08_144445_create_job_approvals_table.php`

**Creates:**
- `job_approvals` table with 14 columns
- Foreign key to `creative_jobs`
- Foreign key to `workflow_stages`
- Foreign keys to `users` (assigned_to, approved_by)
- Composite indexes for performance
- Default values and constraints

---

## Seeder: `WorkflowSeeder`

**Location:** `database/seeders/WorkflowSeeder.php`

**Populates:**
- All 13 workflow stages
- Stage properties (colors, icons, descriptions)
- Approval requirements per stage
- File upload/comment permissions

**Usage:**
```bash
php artisan db:seed --class=WorkflowSeeder
```

---

## Routes

**Location:** `routes/web.php`

```php
// Approval routes (protected by 'auth' middleware)
Route::resource('approvals', JobApprovalController::class)
    ->only(['index', 'show']);

Route::post('/approvals/{approval}/approve', 
    [JobApprovalController::class, 'approve'])->name('approvals.approve');

Route::post('/approvals/{approval}/reject', 
    [JobApprovalController::class, 'reject'])->name('approvals.reject');

Route::post('/approvals/bulk-approve', 
    [JobApprovalController::class, 'bulkApprove'])->name('approvals.bulk-approve');

Route::get('/jobs/{job}/approvals', 
    [JobApprovalController::class, 'jobApprovals'])->name('jobs.approvals');
```

---

## Approval Workflow Logic

### Approval Sequence

1. **Job Created**: System adds job to `email_received` stage
2. **Stage Assignment**: Admin/system moves job to next stage
3. **Approvals Created**: `createInitialApprovals()` creates JobApproval records
4. **Pending**: Assigned users see approvals in their dashboard
5. **Action**: Users approve/reject with comments
6. **Completion Check**: System checks if all required approvals done
7. **Auto-Transition**: If enabled, moves to next stage automatically
8. **Repeat**: For each subsequent stage
9. **Final Delivery**: Reaches delivery stage
10. **Archive**: Job marked complete

### Approval Statuses

| Status | Meaning | Next Action |
|--------|---------|------------|
| pending | Awaiting action | User must approve or reject |
| approved | User approved | Check if all approvals done |
| rejected | User rejected | Return to previous stage |
| commented | Added feedback | Track as activity |

---

## Access Control

### Role-Based Authorization

The `JobApprovalPolicy` enforces:

```php
// Only assigned user can approve
$user->id === $approval->assigned_to_user_id

// Only if approval is pending
$approval->isPending()

// Only if user has required role
$user->hasRole($approval->role)
```

### User Roles

| Role | Approvals | Permissions |
|------|-----------|-------------|
| traffic_manager | Brief, schedule | Approve/reject brief |
| client_service | Requirements, feedback | Approve requirements |
| project_leader | Quality, deliverables | Final review |
| project_manager | Optional checkpoints | Optional approval |
| admin | All approvals | Full system access |

---

## Activity Logging

Every approval action is logged in `job_activities`:

```php
$service->logActivity(
    $job,
    'approval_granted',
    "Approved by {$user->name}",
    $comments,
    $user
);
```

**Logged Events:**
- `approval_granted` - Approval given
- `approval_rejected` - Approval denied
- `stage_changed` - Moved to new stage
- `all_approvals_completed` - All required approvals done

---

## Performance Considerations

### Query Optimization

**Indexes:**
```sql
-- Fast lookup for user's pending approvals
INDEX ON (status, assigned_to_user_id)

-- Fast lookup for job's approvals
INDEX ON (creative_job_id, workflow_stage_id)
```

**Eager Loading:**
```php
// Avoid N+1 queries
$approvals->load('job', 'workflowStage', 'assignedUser', 'approvedBy');
```

### Caching Opportunities

Consider caching:
- Workflow stages (13 static items)
- User's pending approvals (frequently accessed)
- Job completion percentage (recalculated often)

---

## Error Handling

### Common Errors

```php
// Cannot move to stage without approvals
if (!$service->canMoveToStage($job, $stage)) {
    return error('All approvals required first');
}

// Cannot approve without authorization
if (!$user->can('approve', $approval)) {
    return error('Unauthorized action');
}

// Cannot create duplicate approvals
if ($approval->exists()) {
    return error('Approval already exists');
}
```

---

## Future Enhancements

### Phase 2 Features

1. **Email Notifications**
   - Notify when approval is assigned
   - Remind of pending approvals
   - Notify of approvals received

2. **Workflow Templates**
   - Create approval templates by job type
   - Auto-create approvals from templates
   - Customize approval chains

3. **Parallel Approvals**
   - Multiple simultaneous approvals
   - Conditional approvals based on job type
   - Skip approvals based on criteria

4. **Analytics & Reporting**
   - Approval time metrics
   - Bottleneck identification
   - SLA compliance tracking

5. **Escalation**
   - Auto-escalate pending approvals
   - Manager override capabilities
   - Approval deadlines

6. **Integration**
   - Slack notifications
   - Calendar integration
   - API for external systems

---

## Testing

### Unit Test Example

```php
public function test_can_create_approval_for_job()
{
    $job = CreativeJob::factory()->create();
    $stage = WorkflowStage::first();
    $user = User::factory()->create();
    
    $service = app(JobWorkflowService::class);
    $approval = $service->createApprovalRequirement(
        $job,
        $stage,
        'traffic_manager',
        $user
    );
    
    $this->assertNotNull($approval->id);
    $this->assertEquals('pending', $approval->status);
}
```

---

## Troubleshooting

### Approvals Not Creating

- Check if `traffic_manager_id` is set on job
- Verify workflow stage exists
- Check user exists in system

### Cannot Move to Stage

- Verify all required approvals are approved
- Check approval statuses in database
- Review `canMoveToStage()` logic

### Authorization Failed

- Confirm user has correct role
- Check if approval is still pending
- Verify user is assigned_to_user_id

---

## File Locations

```
app/
  ├── Models/
  │   ├── JobApproval.php
  │   └── CreativeJob.php (enhanced)
  ├── Services/
  │   └── JobWorkflowService.php
  ├── Http/Controllers/
  │   └── JobApprovalController.php
  └── Policies/
      └── JobApprovalPolicy.php

database/
  ├── migrations/
  │   └── 2026_07_08_144445_create_job_approvals_table.php
  └── seeders/
      └── WorkflowSeeder.php

routes/
  └── web.php (routes added)

docs/
  ├── TECHNICAL_DOCUMENTATION.md (this file)
  ├── WORKFLOW_DOCUMENTATION.md
  ├── GETTING_STARTED.md
  ├── IMPLEMENTATION_SUMMARY.md
  └── CHANGES_EXPLANATION.md
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-07-08 | Initial release with 13 stages, approval system, audit trail |

---

## Support

For issues or questions:
1. Check `TROUBLESHOOTING` section above
2. Review example usage in tests
3. Check database state with queries
4. Review activity logs in `job_activities` table

