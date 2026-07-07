<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class EmployeeLeave extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'starts_at',
        'ends_at',
        'returns_at',
        'reason',
        'attachment_path',
        'attachment_original_name',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'returns_at' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public const APPROVER_ROLE_CODES = ['SUPER_ADMIN', 'GENERAL_MANAGER', 'HR'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isApprovedOn(?Carbon $date = null): bool
    {
        $date ??= now();

        return $this->status === 'approved'
            && $this->starts_at->startOfDay()->lte($date->copy()->startOfDay())
            && $this->ends_at->endOfDay()->gte($date->copy()->endOfDay());
    }

    public static function canReview(?User $user): bool
    {
        return in_array($user?->role?->code, self::APPROVER_ROLE_CODES, true);
    }
}
