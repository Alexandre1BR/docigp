<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProvidersTable extends Component
{
    public $providers = null;

    public function mount(){
        $this->providers = app(\App\Http\Controllers\Web\Admin\Providers::class)->allBlocked();
    }
    
    public function render()
    {


        return view('livewire.providers.blocked');
    }
}
