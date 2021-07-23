<?php

namespace App\Models;

class AttachedFile extends Model
{
    protected $fillable = [
        'file_id',
        'fileable_id',
        'fileable_type',
        'original_name',
        'mime_type',
    ];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
