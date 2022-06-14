<?php

namespace App\Events;

class CongressmanCreated extends Event
{
    public $congressmanId;

    /**
     * Create a new congressman Comment instance.
     *
     * @param $congressman
     */
    public function __construct($congressman)
    {
        $this->congressmanId = $congressman->id;
    }
}
