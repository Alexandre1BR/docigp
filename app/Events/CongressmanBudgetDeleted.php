<?php

namespace App\Events;

use App\Models\CongressmanLegislature;

class CongressmanBudgetDeleted extends Event
{
    public $congressmanId;

    /**
     * Create a new congressman budget instance.
     *
     * @param $congressmanBudget
     */
    public function __construct($congressmanBudget)
    {
        $this->congressmanId = CongressmanLegislature::find(
            $congressmanBudget->congressman_legislature_id
        )->congressman_id;
    }
}
