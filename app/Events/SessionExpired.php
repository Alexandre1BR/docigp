<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SessionExpired extends Broadcastable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param int
     */
    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function broadcastChannelName()
    {
        if($this->userId) {
            return 'user.' . $this->userId;
        }
    }
}
