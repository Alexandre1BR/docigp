<?php

namespace App\Events;

class CongressmanBudgetCreated extends Event
{
    public $congressmanId;

    /**
     * Create a new congressman budget instance.
     *
     * @param $congressmanBudget
     */
    public function __construct($congressmanBudget)
    {
        $this->congressmanId = $congressmanBudget->congressman->id;
    }
}
