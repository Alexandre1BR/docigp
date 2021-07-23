<?php

namespace App\Models;

use App\Data\Traits\Eventable;
use OwenIt\Auditing\Auditable;
use App\Data\Traits\Joinable;
use App\Data\Traits\Orderable;
use App\Data\Traits\Selectable;
use App\Data\Traits\Filterable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

abstract class Model extends Eloquent implements AuditableContract
{
    use Auditable, Selectable, Joinable, Orderable, Filterable, Eventable;

    protected $dates = ['created_at', 'updated_at'];

    protected $controlCreatedBy = true;

    protected $controlUpdatedBy = true;

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $saved = parent::save();

        $this->fireEvents(
            $this,
            $this->wasRecentlyCreated ? 'Created' : 'Updated'
        );

        return $saved;
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->controlUpdatedBy) {
                $model->updated_by_id = ($user = auth()->user())
                    ? $user->id
                    : 1;
            }
        });

        static::creating(function ($model) {
            if ($model->controlCreatedBy) {
                $model->created_by_id = ($user = auth()->user())
                    ? $user->id
                    : 1;
            }
        });

        static::deleted(function ($model) {
            $model->fireEvents($model, 'Deleted');
        });
    }

    public static function disableGlobalScopes()
    {
    }

    public static function enableGlobalScopes()
    {
    }
}
