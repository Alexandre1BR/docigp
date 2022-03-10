<?php

namespace App\Data\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

trait DataProcessing
{
    protected $plugins = ['data' => [], 'transformation' => []];

    /**
     * @param callable $plugin
     */
    public function addDataPlugin($plugin)
    {
        $this->plugins['data'][] = $plugin;

        return $this;
    }

    /**
     * @param callable $plugin
     */
    public function addTransformationPlugin($plugin)
    {
        $this->plugins['transformation'][] = $plugin;

        return $this;
    }

    public function processTransformationPlugins($data)
    {
        return $this->processPlugins($data, $this->plugins['transformation'], true);
    }

    public function processDataPlugins($data)
    {
        return $this->processPlugins($data, $this->plugins['data'], false);
    }

    public function processPlugins($paginated, $plugins, $convertToArray)
    {
        $transformable =
            $paginated instanceof LengthAwarePaginator
                ? $paginated->getCollection()
                : collect($paginated);

        $transformable->transform(function ($model, $key) use ($plugins, $convertToArray) {
            coollect($plugins)->each(function ($plugin) use (&$model, $key, $convertToArray) {
                if ($convertToArray && $model instanceof Model) {
                    $model = $model->toArray();
                }

                $model = $plugin($model);
            });

            return $model;
        });

        return $paginated instanceof LengthAwarePaginator ? $paginated : $transformable;
    }

    public function transform($data)
    {
        return $this->processTransformationPlugins($this->processDataPlugins($data));
    }
}
