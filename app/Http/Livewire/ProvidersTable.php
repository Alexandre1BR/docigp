<?php

namespace App\Http\Livewire;

use App\Data\Repositories\Providers;
use Livewire\Component;

class ProvidersTable extends Component
{
    public $providers = null;

    public function mount(){
        $this->providers = app(Providers::class)->allBlocked();
    }

    public function render()
    {
        return view('livewire.providers.blocked');
    }
}
