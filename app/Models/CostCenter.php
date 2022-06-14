<?php

namespace App\Models;

use Carbon\Carbon;

class CostCenter extends Model
{
    protected $fillable = [
        'code',
        'name',
        'parent_code',
        'limit',
        'frequency',
        'revoked_at',
        'can_accumulate',
        'created_by_id',
        'updated_by_id',
    ];

    protected $appends = ['formatted_revoked_at'];

    protected $orderBy = ['code' => 'asc'];

    protected $filterableColumns = ['name', 'code'];

    public function getFormattedRevokedAtAttribute()
    {
        return $this->revoked_at ? Carbon::create($this->revoked_at)->format('d/m/Y') : null;
    }
}
