<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmTask extends Model
{
    protected $fillable = ['company_id', 'assigned_to', 'title', 'description', 'status', 'due_at', 'completed_at'];

    protected function casts(): array { return ['due_at' => 'datetime', 'completed_at' => 'datetime']; }

    public function company(): BelongsTo { return $this->belongsTo(CrmCompany::class, 'company_id'); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
}
