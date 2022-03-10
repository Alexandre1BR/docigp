<?php

namespace App\Data\Traits;

use App\Models\User;
use App\Models\ChangeUnread;

trait MarkAsUnread
{
    protected static $markingEnabled = true;

    public static function disableMarking()
    {
        static::$markingEnabled = false;
    }

    public static function enableMarking()
    {
        static::$markingEnabled = true;
    }

    public function markAsUnread()
    {
        if (self::$markingEnabled) {
            User::normal()
                ->get()
                ->each(function ($user) {
                    ChangeUnread::firstOrCreate([
                        'user_id' => $user->id,
                        'congressman_id' => $this->congressman->id,
                    ]);
                });
        }
    }
}
