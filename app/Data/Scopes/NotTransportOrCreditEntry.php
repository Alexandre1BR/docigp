<?php

namespace App\Data\Scopes;

use App\Data\Repositories\CostCenters as CostCentersRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NotTransportOrCreditEntry extends Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (static::$enabled && !($user = current_user())) {
            $builder->whereNotIn(
                $model->getTable() . '.cost_center_id',
                app(CostCentersRepository::class)->getTransportAndCreditIdsArray()
            );
        }
    }
}
