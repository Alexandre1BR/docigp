<?php

namespace App\Listeners;

use App\Events\EntriesChanged;
use App\Events\EntryCommentCreated;
use App\Events\EntryCommentsChanged;
use App\Events\CongressmanBudgetsChanged;
use App\Events\CongressmenChanged;

class OnEntryCommentCreated extends Listener
{
    /**
     * Handle the event.
     *
     * @param  EntryCommentCreated  $event
     * @return void
     */
    public function handle(EntryCommentCreated $event)
    {
        event(new EntryCommentsChanged($event->entryId));
        event(new EntriesChanged($event->congressmanBudgetId));
        event(new CongressmanBudgetsChanged($event->congressmanId));
        event(new CongressmenChanged());
    }
}
