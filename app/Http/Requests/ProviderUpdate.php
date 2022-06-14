<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

use App\Rules\ValidCPF;
use App\Rules\ValidCNPJ;

class ProviderUpdate extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return allows('providers:update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cpfOrCnpj = $this->get('type') == 'PF' ? new ValidCPF() : new ValidCNPJ();
        return [
            'cpf_cnpj' => [
                'required',
                $cpfOrCnpj,
                Rule::unique('providers')->ignore($this->get('id')),
            ],
            'name' => 'required',
            'type' => 'required',
        ];
    }
}
