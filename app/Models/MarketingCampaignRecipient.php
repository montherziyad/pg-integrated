<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingCampaignRecipient extends Model
{
    protected $fillable = ['campaign_id', 'company_id', 'contact_id', 'status', 'sent_at', 'responded_at', 'meta'];

    protected function casts(): array { return ['sent_at' => 'datetime', 'responded_at' => 'datetime', 'meta' => 'array']; }

    public function campaign(): BelongsTo { return $this->belongsTo(MarketingCampaign::class, 'campaign_id'); }
    public function company(): BelongsTo { return $this->belongsTo(CrmCompany::class, 'company_id'); }
    public function contact(): BelongsTo { return $this->belongsTo(CrmContact::class, 'contact_id'); }
}
