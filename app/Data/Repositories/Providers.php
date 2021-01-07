<?php

namespace App\Data\Repositories;

use App\Data\Models\Provider;

class Providers extends Repository
{
    /**
     * @var string
     */
    protected $model = Provider::class;

    public function getAlerj()
    {
        return $this->findByCpfCnpj('30.449.862/0001-67');
    }

    /**
     * Filter Checkboxes
     *
     * @param $query
     * @param array $filter
     * @return mixed
     */
    protected function filterCheckboxes($query, array $filter)
    {
        if (isset($filter['blocked_checkbox'])) {
            $query->where('is_blocked', true);
        }
    }
}
