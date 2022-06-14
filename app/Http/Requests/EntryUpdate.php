<?php

namespace App\Http\Requests;

use App\Support\Constants;

class EntryUpdate extends EntryStore
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return allows('entries:update');
    }
}
