<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'description',
        'default_team',
        'estimated_hours',
        'requires_approval',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'estimated_hours' => 'decimal:2',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name');
    }

    public function jobs()
    {
        return $this->hasMany(CreativeJob::class, 'job_category_id');
    }

    public function descendantJobs()
    {
        return $this->hasManyThrough(
            CreativeJob::class,
            self::class,
            'parent_id',
            'job_category_id'
        );
    }
}
