<?php

namespace App\Listeners;

use App\Events\EntryCommentUpdated;
use App\Events\EntryCommentsChanged;

class OnEntryCommentUpdated extends Listener
{
    /**
     * Handle the event.
     *
     * @param  EntryCommentUpdated  $event
     * @return void
     */
    public function handle(EntryCommentUpdated $event)
    {
        event(new EntryCommentsChanged($event->entryId));
    }
}
