<?php

namespace App\Events;

use App\Models\Entry;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Events\Traits\RateLimited;

class EntriesChanged extends Broadcastable
{
    use Dispatchable, InteractsWithSockets, SerializesModels, RateLimited;

    public $congressmanBudgetId;
    public $entryId;

    public function __construct($congressmanBudgetId)
    {
        $this->congressmanBudgetId = $congressmanBudgetId;
    }

    public function broadcastChannelName()
    {
        return 'congressman_budgets.' . $this->congressmanBudgetId;
    }
}
