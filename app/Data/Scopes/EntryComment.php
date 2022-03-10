<?php

namespace App\Data\Scopes;

use App\Support\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EntryComment extends Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (static::$enabled) {
            if (current_user() != null) {
                if (!is_administrator() && !is_aci()) {
                    $builder->where($model->getTable() . '.created_by_id', current_user()->id);
                }
            } else {
                $builder->limit(0);
            }
        }
    }
}
