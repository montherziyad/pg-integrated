<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailIntake extends Model
{
    protected $fillable = [
        'source', 'message_id', 'conversation_id', 'internet_message_id',
        'sender_email', 'sender_name', 'to_recipients', 'cc_recipients',
        'subject', 'body', 'received_at', 'has_attachments',
        'extracted_job_number', 'validation_passed', 'validation_errors',
        'status', 'rejection_reason', 'reviewed_by', 'reviewed_at',
        'accepted_at', 'rejected_at', 'creative_job_id', 'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'to_recipients' => 'array',
            'cc_recipients' => 'array',
            'validation_errors' => 'array',
            'raw_payload' => 'array',
            'received_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'has_attachments' => 'boolean',
            'validation_passed' => 'boolean',
        ];
    }

    public function attachments()
    {
        return $this->hasMany(EmailIntakeAttachment::class);
    }

    public function validations()
    {
        return $this->hasMany(EmailIntakeValidation::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function job()
    {
        return $this->belongsTo(CreativeJob::class, 'creative_job_id');
    }
}
