<?php

namespace App\Http\Requests;

use App\Services\CpfCnpj\CpfCnpj;
use App\Rules\ValidCPF;
use App\Rules\ValidCNPJ;

class ProviderStore extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return allows('providers:store');
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
            'cpf_cnpj' => ['required', $cpfOrCnpj, 'unique:providers'],
            'name' => 'required',
            'type' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function sanitize(array $all)
    {
        $cpfCnpjFormatter = new CpfCnpj($all['cpf_cnpj']);
        $all['cpf_cnpj'] = $cpfCnpjFormatter->formatAny();
        return parent::sanitize($all);
    }
}
