<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingCampaign extends Model
{
    protected $fillable = [
        'name', 'channel', 'status', 'objective', 'country', 'industry',
        'email_subject', 'message_template', 'cta_url', 'created_by',
        'approved_by', 'approved_at', 'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    public function recipients(): HasMany { return $this->hasMany(MarketingCampaignRecipient::class, 'campaign_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}
