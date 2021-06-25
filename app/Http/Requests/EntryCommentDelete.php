<?php

namespace App\Http\Requests;

use App\Models\EntryComment;
use App\Http\Traits\WithRouteParams;
use Illuminate\Support\Facades\Gate;

class EntryCommentDelete extends Request
{
    use WithRouteParams;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $entryComment = EntryComment::find($this->all()['commentId']);

        return $entryComment &&
            Gate::allows('entry-comments:delete:model', $entryComment) &&
            allows('entry-comments:delete');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
