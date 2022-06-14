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
    public $token;

    /**
     * Create a new event instance.
     *
     * @param int
     */
    public function __construct($token = null)
    {
        $this->token = $token;
    }

    public function broadcastChannelName()
    {
        if($this->token) {
            return 'token.' . $this->token;
        }
    }
}
