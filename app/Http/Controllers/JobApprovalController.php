<?php

namespace App\Http\Controllers;

use App\Models\CreativeJob;
use App\Models\JobApproval;
use App\Services\JobWorkflowService;
use Illuminate\Http\Request;

class JobApprovalController extends Controller
{
    protected JobWorkflowService $workflowService;

    public function __construct(JobWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Get pending approvals for current user
     */
    public function index()
    {
        $approvals = $this->workflowService->getPendingApprovalsForUser(auth()->user());

        return view('approvals.index', [
            'approvals' => $approvals,
            'pendingCount' => $approvals->count(),
        ]);
    }

    /**
     * Show approval details
     */
    public function show(JobApproval $approval)
    {
        $this->authorize('view', $approval);

        return view('approvals.show', [
            'approval' => $approval->load('job', 'workflowStage', 'assignedUser', 'approvedBy'),
            'job' => $approval->job->load('client', 'project', 'assignments', 'activities'),
        ]);
    }

    /**
     * Approve a job
     */
    public function approve(Request $request, JobApproval $approval)
    {
        $this->authorize('approve', $approval);

        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        $this->workflowService->approveJob(
            $approval,
            auth()->user(),
            $request->input('comments')
        );

        return redirect()->route('approvals.show', $approval)
            ->with('success', 'تم الموافقة على المهمة بنجاح');
    }

    /**
     * Reject a job
     */
    public function reject(Request $request, JobApproval $approval)
    {
        $this->authorize('reject', $approval);

        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        $this->workflowService->rejectJob(
            $approval,
            auth()->user(),
            $request->input('comments')
        );

        return redirect()->route('approvals.show', $approval)
            ->with('success', 'تم رفض المهمة وإرسال الملاحظات');
    }

    /**
     * Bulk approve approvals
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'approval_ids' => 'required|array|min:1',
            'approval_ids.*' => 'exists:job_approvals,id',
        ]);

        $approvalIds = $request->input('approval_ids');
        $approved = 0;

        foreach ($approvalIds as $id) {
            $approval = JobApproval::find($id);
            if ($approval && auth()->user()->can('approve', $approval)) {
                $this->workflowService->approveJob($approval, auth()->user());
                $approved++;
            }
        }

        return redirect()->route('approvals.index')
            ->with('success', "تم الموافقة على {$approved} مهام");
    }

    /**
     * Get approvals for a specific job
     */
    public function jobApprovals(CreativeJob $job)
    {
        $approvals = $job->approvals()
            ->orderBy('approval_order')
            ->get();

        return view('jobs.approvals', [
            'job' => $job,
            'approvals' => $approvals,
        ]);
    }
}
