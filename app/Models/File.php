<?php

namespace App\Models;

class File extends Model
{
    protected $fillable = ['hash', 'drive', 'path', 'mime_type'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return config("filesystems.disks.{$this->drive}.url_prefix") .
            $this->path;
    }
}
