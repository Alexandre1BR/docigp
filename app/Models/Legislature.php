<?php

namespace App\Models;

class Legislature extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['number', 'year_start', 'year_end', 'created_by_id'];

    protected $filterableColumns = ['number', 'year_start', 'year_end'];

    protected $orderBy = ['number' => 'asc'];
}
