<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

abstract class Event
{
    use Dispatchable;

    public function __call($method, $arguments)
    {
        if (starts_with($method, 'get')) {
            return $this->hasProperty($property = $this->makeProperty('get', $method))
                ? $this->$property
                : null;
        }

        throw new \Exception("Method {$method} does not exists in " . get_class($this));
    }

    protected function hasProperty($param)
    {
        return property_exists($this, $param);
    }

    protected function makeProperty($string, $method)
    {
        return camel_case(substr(snake_case($method), strlen($string)));
    }
}
