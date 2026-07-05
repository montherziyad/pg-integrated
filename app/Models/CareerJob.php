<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerJob extends Model
{
    protected $fillable = [
        'title',
        'department',
        'location',
        'employment_type',
        'summary',
        'description',
        'requirements',
        'responsibilities',
        'is_published',
        'published_at',
        'closes_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'responsibilities' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'closes_at' => 'datetime',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class);
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->where(fn ($query) => $query->whereNull('closes_at')->orWhere('closes_at', '>=', now()));
    }
}
