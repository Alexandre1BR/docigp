<?php

namespace App\Data\Traits;

use ReflectionClass;
use Illuminate\Support\Str;

trait Eventable
{
    protected static $modelEventsEnabled = true;

    /**
     * Disable model events.
     */
    public static function disableEvents()
    {
        static::$modelEventsEnabled = false;
    }

    /**
     * Enable model events.
     */
    public static function enableEvents()
    {
        static::$modelEventsEnabled = true;
    }

    /**
     * @param $model
     * @param string $type
     */
    protected function fireEvents($model, $type = null)
    {
        $type = $this->inferEventType($model, $type);

        if (static::$modelEventsEnabled) {
            $this->fireEventForModel($model, $type);

            //$this->fireEventForTable($model, 'Changed', true);
        }

        if (method_exists($this, 'fireEventsForRelationships')) {
            $this->fireEventsForRelationships($model, $type);
        }
    }

    protected function fireEventForModel($model, $eventType)
    {
        $eventClass = $this->getModelEventClass($model, $eventType);

        if (class_exists($eventClass)) {
            event(new $eventClass($model));
        }
    }

    protected function getModelEventClass($model, $eventType)
    {
        $reflect = new ReflectionClass($model);

        $className = $reflect->getShortName();

        return "App\\Events\\{$className}{$eventType}";
    }

    protected function getTableEventClass($model, $eventType)
    {
        $tableName = Str::studly($model->getTable());

        return "App\\Events\\{$tableName}{$eventType}";
    }

    protected function fireEventForTable($model, $eventType, $plural = false)
    {
        $eventClass = $this->getTableEventClass($model, $eventType);

        if (class_exists($eventClass)) {
            event(new $eventClass($model->id));
        }
    }

    protected function inferEventType($model, $type)
    {
        return filled($type) ? $type : ($model->wasRecentlyCreated ? 'Created' : 'Updated');
    }
}
