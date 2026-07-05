<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingCampaign extends Model
{
    protected $fillable = ['name', 'channel', 'status', 'message_template', 'created_by', 'scheduled_at'];

    protected function casts(): array { return ['scheduled_at' => 'datetime']; }

    public function recipients(): HasMany { return $this->hasMany(MarketingCampaignRecipient::class, 'campaign_id'); }
}
