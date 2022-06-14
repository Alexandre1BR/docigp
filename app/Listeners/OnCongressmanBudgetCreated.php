<?php

namespace App\Listeners;

use App\Events\CongressmanBudgetsChanged;
use App\Events\CongressmanBudgetCreated;
use App\Events\CongressmenChanged;

class OnCongressmanBudgetCreated extends Listener
{
    /**
     * Handle the event.
     *
     * @param  CongressmanBudgetCreated  $event
     * @return void
     */
    public function handle(CongressmanBudgetCreated $event)
    {
        event(new CongressmanBudgetsChanged($event->congressmanId));
        event(new CongressmenChanged($event->congressmanId));
    }
}
