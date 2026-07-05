<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiInteraction extends Model
{
    protected $fillable = ['provider', 'agent', 'subject_type', 'subject_id', 'user_id', 'prompt', 'response', 'meta'];

    protected function casts(): array { return ['meta' => 'array']; }

    public function subject(): MorphTo { return $this->morphTo(); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
