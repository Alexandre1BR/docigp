<?php

namespace App\Events;

use App\Models\EntryDocument;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Events\Traits\RateLimited;

class EntryDocumentsChanged extends Broadcastable
{
    use Dispatchable, InteractsWithSockets, SerializesModels, RateLimited;

    public $entryId;

    public function __construct($entryId)
    {
        $this->entryId = $entryId;
    }

    public function broadcastChannelName()
    {
        return 'entries.' . $this->entryId;
    }
}
