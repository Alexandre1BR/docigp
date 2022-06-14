<?php

namespace App\Events;

class EntryCreated extends Event
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
        $this->entryId = $entry->id;
        $this->congressmanBudgetId = $entry->congressman_budget_id;
        $this->congressmanId = $entry->congressmanBudget->congressman->id;
    }
}
