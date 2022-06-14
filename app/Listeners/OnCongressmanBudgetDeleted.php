<?php

namespace App\Listeners;

use App\Events\CongressmanBudgetDeleted;
use App\Events\CongressmanBudgetsChanged;

class OnCongressmanBudgetDeleted extends Listener
{
    /**
     * Handle the event.
     *
     * @param  CongressmanBudgetDeleted  $event
     * @return void
     */
    public function handle(CongressmanBudgetDeleted $event)
    {
        event(new CongressmanBudgetsChanged($event->congressmanId));
        event(new CongressmenChanged($event->congressmanId));
    }
}
