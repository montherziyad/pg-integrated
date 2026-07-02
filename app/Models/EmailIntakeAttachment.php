<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailIntakeAttachment extends Model
{
    protected $fillable = [
        'email_intake_id', 'outlook_attachment_id', 'name', 'content_type',
        'size', 'is_inline', 'is_brief', 'storage_disk', 'storage_path', 'sha256',
    ];

    protected function casts(): array
    {
        return ['is_inline' => 'boolean', 'is_brief' => 'boolean'];
    }

    public function intake()
    {
        return $this->belongsTo(EmailIntake::class, 'email_intake_id');
    }
}
