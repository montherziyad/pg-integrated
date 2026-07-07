<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeave;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeLeaveController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $canReview = EmployeeLeave::canReview($user);
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $leaves = EmployeeLeave::query()
            ->with(['employee.team', 'employee.role', 'reviewer'])
            ->when(! $canReview, fn ($query) => $query->where('user_id', $user->id))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('leave_type', 'ilike', "%{$search}%")
                        ->orWhere('status', 'ilike', "%{$search}%")
                        ->orWhere('reason', 'ilike', "%{$search}%")
                        ->orWhereHas('employee', fn ($employee) => $employee
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%"));
                });
            })
            ->latest('starts_at')
            ->paginate(20)
            ->withQueryString();

        return view('employee-leaves.index', [
            'leaves' => $leaves,
            'canReview' => $canReview,
            'users' => $canReview ? User::query()->where('is_active', true)->orderBy('name')->get() : collect([$user]),
            'leaveTypes' => $this->leaveTypes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $canReview = EmployeeLeave::canReview($request->user());

        $data = $request->validate([
            'user_id' => [$canReview ? 'required' : 'nullable', 'exists:users,id'],
            'leave_type' => ['required', Rule::in(array_keys($this->leaveTypes()))],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'returns_at' => ['required', 'date', 'after:ends_at'],
            'reason' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:10240'],
        ]);

        $data['user_id'] = $canReview ? $data['user_id'] : $request->user()->id;
        $data['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment_path'] = $file->store('employee-leaves', 'public');
            $data['attachment_original_name'] = $file->getClientOriginalName();
        }

        EmployeeLeave::query()->create($data);

        return back()->with('success', 'Leave request submitted.');
    }

    public function approve(Request $request, EmployeeLeave $leave): RedirectResponse
    {
        abort_unless(EmployeeLeave::canReview($request->user()), 403);

        $data = $request->validate([
            'review_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $leave->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_note' => $data['review_note'] ?? null,
        ]);

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, EmployeeLeave $leave): RedirectResponse
    {
        abort_unless(EmployeeLeave::canReview($request->user()), 403);

        $data = $request->validate([
            'review_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $leave->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_note' => $data['review_note'] ?? null,
        ]);

        return back()->with('success', 'Leave request rejected.');
    }

    public function cancel(Request $request, EmployeeLeave $leave): RedirectResponse
    {
        abort_unless($leave->user_id === $request->user()->id || EmployeeLeave::canReview($request->user()), 403);
        abort_unless($leave->status === 'pending', 403);

        $leave->update(['status' => 'cancelled']);

        return back()->with('success', 'Leave request cancelled.');
    }

    public function download(Request $request, EmployeeLeave $leave)
    {
        abort_unless($leave->user_id === $request->user()->id || EmployeeLeave::canReview($request->user()), 403);
        abort_unless($leave->attachment_path && Storage::disk('public')->exists($leave->attachment_path), 404);

        return Storage::disk('public')->download($leave->attachment_path, $leave->attachment_original_name ?: basename($leave->attachment_path));
    }

    private function leaveTypes(): array
    {
        return [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'emergency' => 'Emergency Leave',
            'unpaid' => 'Unpaid Leave',
        ];
    }
}
