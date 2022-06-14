<?php

namespace App\Listeners;

use App\Events\CongressmanDeleted;
use App\Events\CongressmenChanged;

class OnCongressmanDeleted extends Listener
{
    /**
     * Handle the event.
     *
     * @param  CongressmanDeleted  $event
     * @return void
     */
    public function handle(CongressmanDeleted $event)
    {
        event(new CongressmenChanged($event->congressmanId));
    }
}
