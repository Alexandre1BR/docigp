<?php

namespace App\Listeners;

use App\Events\EntriesChanged;
use App\Events\EntryCommentDeleted;
use App\Events\EntryCommentsChanged;
use App\Events\CongressmanBudgetsChanged;
use App\Events\CongressmenChanged;

class OnEntryCommentDeleted extends Listener
{
    /**
     * Handle the event.
     *
     * @param  EntryCommentDeleted  $event
     * @return void
     */
    public function handle(EntryCommentDeleted $event)
    {
        event(new EntryCommentsChanged($event->entryId));
        event(new EntriesChanged($event->congressmanBudgetId));
        event(new CongressmanBudgetsChanged($event->congressmanId));
        event(new CongressmenChanged());
    }
}
