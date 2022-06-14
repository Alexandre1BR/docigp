<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;

abstract class Broadcastable extends Event implements ShouldBroadcast
{
    use InteractsWithSockets;
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if (method_exists($this, 'broadcastChannelName')) {
            //            info(
            //                'Broadcasting event ' .
            //                    class_basename($this) .
            //                    ' on channel => ' .
            //                    $this->broadcastChannelName()
            //            );

            return new Channel($this->broadcastChannelName());
        } else {
            error(
                'Tried to broadcast ' .
                    class_basename($this) .
                    ', but the class doesn\'t have the method broadcastChannelName()'
            );
        }
    }
}
