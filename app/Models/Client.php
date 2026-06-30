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
}