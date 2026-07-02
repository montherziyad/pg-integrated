<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailIntakeTrafficMember extends Model
{
    protected $fillable = ['user_id', 'outlook_email', 'is_active', 'receives_notifications'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'receives_notifications' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
