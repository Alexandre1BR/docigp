<?php

namespace App\Data\Repositories;

use App\Models\Department as DepartmentModel;

class Departments extends Repository
{
    /**
     * @var string
     */
    protected $model = DepartmentModel::class;

    public function createDepartmentFromCongressman($congressman)
    {
        return $this->model::firstOrCreate([
            'name' => ($name = $this->normalizeName($congressman['Nome'])),
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);
    }
}
