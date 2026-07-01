<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'creative_job_id',
        'uploaded_by',
        'file_name',
        'original_name',
        'file_type',
        'mime_type',
        'file_size',
        'storage_type',
        'storage_path',
        'version',
        'asset_stage',
        'is_final',
        'notes',
    ];

    public function job()
    {
        return $this->belongsTo(CreativeJob::class, 'creative_job_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}