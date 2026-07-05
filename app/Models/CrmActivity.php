<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmActivity extends Model
{
    protected $fillable = ['company_id', 'contact_id', 'user_id', 'type', 'channel', 'summary', 'body', 'activity_at', 'meta'];

    protected function casts(): array { return ['activity_at' => 'datetime', 'meta' => 'array']; }

    public function company(): BelongsTo { return $this->belongsTo(CrmCompany::class, 'company_id'); }
    public function contact(): BelongsTo { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
