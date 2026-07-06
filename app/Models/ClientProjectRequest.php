<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProjectRequest extends Model
{
    protected $fillable = [
        'request_number',
        'client_id',
        'project_id',
        'service_name',
        'type',
        'title',
        'brief',
        'target_country',
        'desired_launch_date',
        'budget_range',
        'priority',
        'status',
        'deliverables',
        'attachments',
        'external_links',
        'admin_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'desired_launch_date' => 'date',
            'deliverables' => 'array',
            'attachments' => 'array',
            'external_links' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
