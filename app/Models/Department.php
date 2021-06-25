<?php

namespace App\Models;

class Department extends Model
{
    protected $fillable = ['name', 'initials'];

    public function congressman()
    {
        $this->hasOne(Congressman::class);
    }

    public function users()
    {
        $this->hasMany(Department::class);
    }

    public function isCongressmanCabinet()
    {
        return filled($this->congressman_id);
    }
}
