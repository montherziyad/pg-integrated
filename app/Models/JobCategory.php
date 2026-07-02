<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    public function jobs()
    {
        return $this->hasMany(CreativeJob::class, 'job_category_id');
    }
}
