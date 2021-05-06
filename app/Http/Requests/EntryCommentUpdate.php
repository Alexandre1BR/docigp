<?php

namespace App\Http\Requests;

use App\Data\Models\EntryComment;
use App\Http\Traits\WithRouteParams;
use Illuminate\Support\Facades\Gate;

class EntryCommentUpdate extends Request
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
            Gate::allows('entry-comments:update:model', $entryComment) &&
            allows('entry-comments:update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['text' => 'required'];
    }
}
