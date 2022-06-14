<?php

namespace App\Http\Requests;

class CostCenterStore extends Request
{
    public function authorize()
    {
        return allows('cost-centers:store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required | unique:cost_centers',
            'name' => 'required',
            'can_accumulate' => 'required',
        ];
    }
}
