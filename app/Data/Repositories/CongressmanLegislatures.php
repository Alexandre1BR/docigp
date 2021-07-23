<?php

namespace App\Data\Repositories;

use App\Models\CongressmanLegislature;

class CongressmanLegislatures extends Repository
{
    /**
     * @var string
     */
    protected $model = CongressmanLegislature::class;

    public function remmoveFromLegislature($congressman_id, $ended_at)
    {
        $legislature_id = app(Legislatures::class)->getCurrent()->id;

        $model = $this->model
            ::where('congressman_id', $congressman_id)
            ->where('legislature_id', $legislature_id)
            ->whereNull('ended_at')
            ->first();
        $model->ended_at = $ended_at;

        $model->save();

        return $model;
    }

    public function includeInLegislature($congressman_id, $started_at)
    {
        $model = $this->model();

        $model->started_at = $started_at;
        $model->legislature_id = app(Legislatures::class)->getCurrent()->id;

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
