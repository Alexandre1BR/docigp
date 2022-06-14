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
}
