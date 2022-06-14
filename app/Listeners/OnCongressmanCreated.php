<?php

namespace App\Listeners;

use App\Events\CongressmenChanged;
use App\Events\CongressmanCreated;

class OnCongressmanCreated extends Listener
{
    /**
     * Handle the event.
     *
     * @param  CongressmanCreated  $event
     * @return void
     */
    public function handle(CongressmanCreated $event)
    {
        event(new CongressmenChanged($event->congressmanId));
    }
}
