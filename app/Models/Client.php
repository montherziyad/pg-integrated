<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class Client extends Authenticatable
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
            'password' => 'hashed',
            'is_active' => 'boolean',
            'portal_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
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
