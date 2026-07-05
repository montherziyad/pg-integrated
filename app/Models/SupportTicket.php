<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = ['ticket_number', 'client_id', 'company_id', 'assigned_to', 'subject', 'status', 'priority', 'channel', 'ai_summary', 'closed_at'];

    protected function casts(): array { return ['closed_at' => 'datetime']; }

    protected static function booted(): void
    {
        static::creating(function (SupportTicket $ticket) {
            if (! $ticket->ticket_number) {
                $ticket->ticket_number = 'PG-TCK-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function messages(): HasMany { return $this->hasMany(SupportMessage::class, 'ticket_id'); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function company(): BelongsTo { return $this->belongsTo(CrmCompany::class, 'company_id'); }
}
