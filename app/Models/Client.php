<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'client_code',
        'name',
        'branch_id',
        'account_manager_id',
        'industry',
        'email',
        'phone',
        'is_active',
    ];

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
}
