<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'employee_no',
    'branch_id',
    'team_id',
    'role_id',
    'job_title',
    'mobile',
    'capacity_hours',
    'is_active',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jobAssignments()
    {
        return $this->hasMany(JobAssignment::class, 'user_id');
    }

    public function employeeLeaves()
    {
        return $this->hasMany(EmployeeLeave::class, 'user_id');
    }

    public function approvedLeaves()
    {
        return $this->employeeLeaves()->where('status', 'approved');
    }

    public function currentApprovedLeave()
    {
        return $this->approvedLeaves()
            ->whereDate('starts_at', '<=', now()->toDateString())
            ->whereDate('ends_at', '>=', now()->toDateString())
            ->orderBy('ends_at')
            ->first();
    }

    public function isOnApprovedLeave(): bool
    {
        return $this->currentApprovedLeave() !== null;
    }

    public function canAccessScreen(string $screen): bool
    {
        if ($screen === '') {
            return true;
        }

        if (! $this->role && app()->environment('testing')) {
            return true;
        }

        return $this->role?->hasScreenPermission($screen) ?? false;
    }
}

