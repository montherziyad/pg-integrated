<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ClientVerifyEmailNotification;

#[Hidden(['password', 'remember_token'])]
class Client extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'client_code',
        'name',
        'company_name',
        'contact_person',
        'branch_id',
        'account_manager_id',
        'industry',
        'country',
        'city',
        'website',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'avatar_path',
        'company_profile',
        'is_active',
        'portal_enabled',
        'last_login_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'portal_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new ClientVerifyEmailNotification());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function clientServiceUsers()
    {
        return $this->belongsToMany(User::class, 'client_service_user')->withTimestamps();
    }

    public function clientServiceNames(): string
    {
        $team = $this->relationLoaded('clientServiceUsers') ? $this->clientServiceUsers : collect();

        return $team->pluck('name')->filter()->join(', ') ?: ($this->accountManager?->name ?? '-');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function creativeJobs()
    {
        return $this->hasMany(CreativeJob::class);
    }

    public function projectRequests()
    {
        return $this->hasMany(ClientProjectRequest::class);
    }
}
