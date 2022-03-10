<?php

namespace App\Data\Repositories;

use App\Models\CongressmanLegislature;

class CongressmanLegislatures extends Repository
{
    /**
     * @var string
     */
    protected $model = CongressmanLegislature::class;


    public function includeInLegislature($legislature_id, $congressman_id, $started_at)
    {
        $model = $this->model();

        $model->started_at = $started_at;
        $model->legislature_id = $legislature_id;

        $model->congressman_id = $congressman_id;

        $model->save();

        return $model;
    }

    public function isInCurrentLegislature($congressman_id)
    {
        $model = $this->model
            ::where('congressman_id', $congressman_id)
            ->where(
                'legislature_id',
                app(Legislatures::class)->getCurrent()->id
            )
            ->whereNull('ended_at')
            ->first();
        return !is_null($model);
    }
}
