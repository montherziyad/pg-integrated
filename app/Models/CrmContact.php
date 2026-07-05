<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmContact extends Model
{
    protected $fillable = ['company_id', 'name', 'position', 'email', 'phone', 'whatsapp', 'is_primary'];

    protected function casts(): array { return ['is_primary' => 'boolean']; }

    public function company(): BelongsTo { return $this->belongsTo(CrmCompany::class, 'company_id'); }
}
