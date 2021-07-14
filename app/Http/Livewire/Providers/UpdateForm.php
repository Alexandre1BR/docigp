<?php

namespace App\Http\Livewire\Providers;

use App\Http\Livewire\BaseUpdateForm;
use App\Models\Provider;
use App\Services\Zipcode\Service as Zipcode;

class UpdateForm extends BaseUpdateForm
{
    public Provider $provider;

    public $focus = 'zipcode';

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

            $this->focus('number');
        } else {
            $this->focus('zipcode');
        }
    }

    public function clicando()
    {
        $this->dispatchBrowserEvent('focus-field', ['field' => 'number']);
    }

    protected function getComponentVariables()
    {
        return [
            'provider' => $this->provider,
            'entries' => $this->provider->entries()->paginate(7),
        ];
    }

    protected function fillAddress()
    {
        $this->zipcode = $this->provider->zipcode;
        $this->street = $this->provider->street;
        $this->city = $this->provider->city;
        $this->number = $this->provider->number;
        $this->state = $this->provider->state;
        $this->complement = $this->provider->complement;
        $this->neighborhood = $this->provider->neighborhood;
    }

    public function render()
    {
        //        $this->fillAddress();
        return view('livewire.providers.form')->with($this->getViewVariables());
    }

    public function mount()
    {
        $this->fillAddress();
    }
}
