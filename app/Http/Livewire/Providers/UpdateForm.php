<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use App\Services\Zipcode\Service as Zipcode;

class UpdateForm extends CreateForm
{
    public $mode = 'update';

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

    public function mount()
    {
        $this->fillAddress();
    }
}
