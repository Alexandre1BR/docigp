<?php

namespace App\Http\Livewire;

use App\Models\Provider;
use Livewire\Component;

class ProviderForm extends Component
{
    public $provider;

    public function mount()
    {
         $this->provider = new Provider();
    }
    public function render()
    {

        return view('livewire.providers.form');
    }
}
