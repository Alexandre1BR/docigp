<?php

namespace App\Models;

class ChangeUnread extends Model
{
    protected $table = 'changes_unread';

    protected $fillable = ['user_id', 'congressman_id'];

    protected $controlCreatedBy = false;

    protected $controlUpdatedBy = false;
}
