<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmCompany extends Model
{
    protected $fillable = ['name', 'industry', 'country', 'website', 'linkedin_url', 'source', 'status', 'lead_score', 'expected_value', 'owner_id', 'last_contacted_at', 'next_follow_up_at'];

    protected function casts(): array
    {
        return [
            'expected_value' => 'decimal:2',
            'last_contacted_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function contacts(): HasMany { return $this->hasMany(CrmContact::class, 'company_id'); }
    public function activities(): HasMany { return $this->hasMany(CrmActivity::class, 'company_id')->latest(); }
    public function tasks(): HasMany { return $this->hasMany(CrmTask::class, 'company_id'); }
}
