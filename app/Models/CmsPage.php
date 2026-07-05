<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPage extends Model
{
    protected $fillable = ['key', 'title', 'slug', 'sections', 'seo', 'is_published', 'updated_by'];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'seo' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
