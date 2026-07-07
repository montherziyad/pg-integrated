<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'event_date',
        'title',
        'title_ar',
        'type',
        'country',
        'audience',
        'roles',
        'team_ids',
        'note',
        'action',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'audience' => 'array',
        'roles' => 'array',
        'team_ids' => 'array',
        'is_active' => 'boolean',
    ];
}
