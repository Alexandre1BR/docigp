<?php

namespace App\Events;

use App\Models\CongressmanBudget;

class EntryDeleted extends Event
{
    public $entryId;
    public $congressmanBudgetId;
    public $congressmanId;

    /**
     * Create a new entry instance.
     *
     * @param $entry
     */
    public function __construct($entry)
    {
        $entry = (object) $entry->toArray();

        $this->entryId = $entry->id;
        $this->congressmanBudgetId = $entry->congressman_budget_id;

        $this->congressmanId = CongressmanBudget::find($this->congressmanBudgetId)->congressman->id;
    }
}
