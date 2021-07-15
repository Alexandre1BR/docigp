<?php

namespace App\Http\Livewire\Providers;

use App\Http\Livewire\BaseForm;
use App\Models\Provider;
use App\Services\Zipcode\Service as Zipcode;

class CreateForm extends BaseForm
{
    public Provider $provider;

    public $cpfCnpj;
    public $type;
    public $name;
    public $is_blocked;
    public $zipcode;
    public $street;
    public $city;
    public $number;
    public $state;
    public $complement;
    public $neighborhood;

    public function fillModel()
    {
        $this->cpfCnpj = $this->provider->cpfCnpj ?? '';
        $this->type = $this->provider->type ?? '';
        $this->name = $this->provider->name ?? '';
        $this->is_blocked = $this->provider->is_blocked ?? '';
        $this->zipcode = $this->provider->zipcode ?? '';
        $this->street = $this->provider->street ?? '';
        $this->city = $this->provider->city ?? '';
        $this->number = $this->provider->number ?? '';
        $this->state = $this->provider->state ?? '';
        $this->complement = $this->provider->complement ?? '';
        $this->neighborhood = $this->provider->neighborhood ?? '';
    }

    function updatedZipcode($newValue)
    {
        //        try {
        if ($result = app(Zipcode::class)->get(only_numbers($newValue))) {
            $this->street = $result['logradouro'];
            $this->city = $result['localidade'];
            $this->state = $result['uf'];
            $this->neighborhood = $result['bairro'];

            $this->focus('number');

            $this->resetErrorBag('zipcode');
        } else {
            $this->focus('zipcode');

            //            info('CEP n enc');

            $this->addError('zipcode', 'CEP não encontrado');
        }
        //        }catch(\Exception $e){
        //            info('Exception na busca de CEP');
        //        }
    }

    protected function getComponentVariables()
    {
        return [
            'provider' => $this->provider,
        ];
    }

    public function mount()
    {
        $this->provider = new Provider();
        $this->fillModel();
    }

    public function render()
    {
        return view('livewire.providers.form')->with($this->getViewVariables());
    }
}
