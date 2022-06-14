<?php

namespace App\Data\Repositories;

use App\Models\Audit;

class Audits extends Repository
{
    /**
     * @var string
     */
    protected $model = Audit::class;
}
