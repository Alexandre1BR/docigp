<?php

namespace App\Events;

use App\Models\Entry;
use App\Models\CongressmanBudget;

class EntryCommentCreated extends Event
{
    public $entryCommentId;
    public $entryId;
    public $congressmanBudgetId;
    public $congressmanId;

    /**
     * Create a new entry Comment instance.
     *
     * @param $entryComment
     */
    public function __construct($entryComment)
    {
        $this->entryCommentId = $entryComment->id;
        $this->entryId = $entryComment->entry_id;

        $this->congressmanBudgetId = Entry::find(
            $entryComment->entry_id
        )->congressman_budget_id;

        $this->congressmanId = CongressmanBudget::find(
            $this->congressmanBudgetId
        )->congressman->id;
    }
}
