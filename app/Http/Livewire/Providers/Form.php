<?php

namespace App\Http\Livewire\Providers;

use App\Data\Repositories\Entries;
use App\Data\Repositories\Providers as ProvidersRepository;
use App\Http\Livewire\BaseForm;
use App\Services\Zipcode\Service as Zipcode;

class Form extends BaseForm
{
    public $providerId;
    public $provider;
    public $entries;

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

    public function render()
    {
        $this->provider = app(ProvidersRepository::class)->findById($this->providerId);

        //        $this->entries = app(Entries::class)
        //            ->newQuery()
        //            ->where('entries.provider_id', $this->providerId)
        //            ->paginate(5);

        //        dd($this->entries);

        return view('livewire.providers.form')->with([
            'provider' => $this->provider,
            //            'entries' => $this->entries,
        ]);
    }

    public function mount()
    {
        //        $this->zipcode = $this->provider->zipcode;
        //        $this->street = $this->provider->street;
        //        $this->city = $this->provider->city;
        //        $this->number = $this->provider->number;
        //        $this->state = $this->provider->state;
        //        $this->complement = $this->provider->complement;
        //        $this->neighborhood = $this->provider->neighborhood;
    }
}
