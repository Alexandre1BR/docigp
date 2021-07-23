<?php

namespace App\Data\Repositories;

use App\Models\CongressmanSettings as CongressmanSettingsModel;

class CongressmanSettings extends Repository
{
    /**
     * @var string
     */
    protected $model = CongressmanSettingsModel::class;
}
