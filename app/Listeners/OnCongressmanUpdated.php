<?php

namespace App\Listeners;

use App\Events\CongressmanUpdated;
use App\Events\CongressmenChanged;

class OnCongressmanUpdated extends Listener
{
    /**
     * Handle the event.
     *
     * @param  CongressmanUpdated  $event
     * @return void
     */
    public function handle(CongressmanUpdated $event)
    {
        event(new CongressmenChanged($event->congressmanId));
    }
}
