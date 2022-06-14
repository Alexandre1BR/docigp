<?php

namespace App\Events\Traits;

use Illuminate\Support\Facades\Redis;

trait RateLimited
{
    public function broadcastWhen()
    {
        $run = true;

        //        TODO:Throttle this if necessary!
        //        Redis::throttle(class_basename($this) . 'Broadcast')
        //            ->allow(config('app.events.throttle.allow'))
        //            ->every(config('app.events.throttle.every'))
        //            ->then(
        //                function () {
        //                    info(class_basename($this));
        //                },
        //                function () use (&$run) {
        //                    info(class_basename($this) . ' discarded');
        //                    $run = false;
        //                }
        //            );

        return $run;
    }
}
