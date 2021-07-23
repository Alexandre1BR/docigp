<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CongressmanLegislature extends Model
{

    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'congressman_id',
        'legislature_id',
        'started_at',
        'ended_at',
        'created_by_id',
        'updated_by_id',
    ];

    protected $dates = ['started_at', 'ended_at'];

    public function legislature()
    {
        return $this->belongsTo(Legislature::class);
    }

    public function congressman()
    {
        return $this->belongsTo(Congressman::class);
    }
}
