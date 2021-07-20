<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderBlockPeriod extends Model
{
    protected $fillable = [
        'provider_id',
        'start_date',
        'end_date',
        'created_by_id',
        'updated_by_id',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
