<?php

namespace App\Data\Models;

use App\Data\Traits\MarkAsUnread;
use App\Data\Traits\ModelActionable;
use App\Data\Scopes\EntryComment as EntryCommentScope;
use Carbon\Carbon;

class EntryComment extends Model
{
    use ModelActionable, MarkAsUnread;

    protected $fillable = ['entry_id', 'text', 'created_by_id', 'updated_by_id'];

    protected $selectColumns = ['entry_comments.*'];

    protected $with = ['user'];

    protected $dates = ['created_at'];

    protected $orderBy = ['id' => 'asc'];

    protected $joins = [];

    protected $appends = ['formatted_created_at', 'creator_is_congressman'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EntryCommentScope());
    }

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

    public function getCreatorIsCongressmanAttribute()
    {
        return !!$this->user->congressman_id;
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function congressman()
    {
        return $this->entry->congressmanBudget->congressman();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
