<?php

namespace App\Models;

use App\Data\Traits\ModelActionable;

class EntryType extends Model
{
    use ModelActionable;

    protected $fillable = ['name', 'document_number_required', 'created_by_id', 'updated_by_id'];

    protected $orderBy = ['name' => 'asc'];

    protected $filterableColumns = ['name'];
}
