<?php

namespace App\Http\Requests;

class AnnualReportWithCongressman extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['year' => 'required', 'congressman_id' => 'required'];
    }
}
