<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailIntakeValidation extends Model
{
    protected $fillable = ['email_intake_id', 'rule', 'passed', 'message', 'context'];

    protected function casts(): array
    {
        return ['passed' => 'boolean', 'context' => 'array'];
    }

    public function intake()
    {
        return $this->belongsTo(EmailIntake::class, 'email_intake_id');
    }
}
