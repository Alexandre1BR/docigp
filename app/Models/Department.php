<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{

    use HasFactory;

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
