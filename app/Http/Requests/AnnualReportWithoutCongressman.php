<?php

namespace App\Http\Requests;

class AnnualReportWithoutCongressman extends Request
{
    public function authorize()
    {
        return allows('annual-reports:generate');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['year' => 'required'];
    }
}
