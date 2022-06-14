<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CostCenterUpdate extends CostCenterStore
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', Rule::unique('cost_centers')->ignore($this->get('id'))],
            'name' => 'required',
            'can_accumulate' => 'required',
        ];
    }
}
