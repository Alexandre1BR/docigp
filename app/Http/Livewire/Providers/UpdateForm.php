<?php

namespace App\Http\Livewire\Providers;

use App\Http\Livewire\BaseUpdateForm;
use App\Models\Provider;
use App\Services\Zipcode\Service as Zipcode;

class UpdateForm extends BaseUpdateForm
{
    public Provider $provider;

    public $zipcode;
    public $street;
    public $city;
    public $number;
    public $state;
    public $complement;
    public $neighborhood;

    function updatedZipcode($newValue)
    {
        if ($result = app(Zipcode::class)->get($newValue)) {
            $this->street = $result['logradouro'];
            $this->city = $result['localidade'];
            $this->state = $result['uf'];
            $this->neighborhood = $result['bairro'];
        }
    }

    protected function getComponentVariables()
    {
        return [
            'provider' => $this->provider,
            'entries' => $this->provider->entries()->paginate(7),
        ];
    }

    public function render()
    {
        //        dd($this->isEditing);

        return view('livewire.providers.form')->with($this->getViewVariables());
    }
}
