<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Data\Repositories\CostCenters as CostCentersRepository;

class CostCenters extends Controller
{
    /**
     * Get all data
     *
     * @return array
     */
    public function all()
    {
        return app(CostCentersRepository::class)
            ->filterControlTypes()
            ->disablePagination()
            ->all();
    }
}
