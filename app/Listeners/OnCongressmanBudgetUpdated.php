<?php

namespace App\Listeners;

use App\Events\CongressmanBudgetUpdated;
use App\Events\CongressmanBudgetsChanged;
use App\Events\CongressmenChanged;

class OnCongressmanBudgetUpdated extends Listener
{
    /**
     * Handle the event.
     *
     * @param  CongressmanBudgetUpdated  $event
     * @return void
     */
    public function handle(CongressmanBudgetUpdated $event)
    {
        event(new CongressmanBudgetsChanged($event->congressmanId));
        event(new CongressmenChanged($event->congressmanId));
    }
}
