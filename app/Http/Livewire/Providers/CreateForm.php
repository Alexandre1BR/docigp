<?php

namespace App\Http\Livewire\Providers;

use App\Http\Livewire\BaseForm;
use App\Models\Provider;
use App\Models\ProviderBlockPeriod;
use App\Services\CpfCnpj\CpfCnpj;
use App\Services\Zipcode\Service as Zipcode;

class CreateForm extends BaseForm
{
    public $start_date;
    public $end_date;
    public $provider_id;
    public $blockedPeriods;

    public function store()
    {
        $validatedData = $this->validate([
            'provider_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
        ]);

        ProviderBlockPeriod::create($validatedData);

        session()->flash('message', 'Período criado com sucesso.');

        $this->resetInputFields();

        $this->emit('userStore'); // Close model to using to jquery
        $this->resetPage();
    }

    private function resetInputFields()
    {
        $this->start_date = null;
        $this->end_date = null;
    }

    public function delete($id)
    {
        if ($id) {
            ProviderBlockPeriod::where('id', $id)->delete();
            session()->flash('message', 'Users Deleted Successfully.');
        }
        $this->resetPage();
    }

    public Provider $provider;

    public $cpfCnpj;
    public $type;
    public $name;
    public $zipcode;
    public $street;
    public $city;
    public $number;
    public $state;
    public $complement;
    public $neighborhood;

    public function fillModel()
    {
        $this->cpfCnpj = is_null(old('cpf_cnpj'))
            ? $this->provider->cpf_cnpj ?? ''
            : old('cpf_cnpj');
        $this->type = is_null(old('type')) ? $this->provider->type ?? '' : old('type');
        $this->name = is_null(old('name')) ? $this->provider->name ?? '' : old('name') ?? '';
        $this->zipcode = is_null(old('zipcode'))
            ? mask_zipcode($this->provider->zipcode) ?? ''
            : mask_zipcode(old('zipcode'));
        $this->street = is_null(old('street'))
            ? $this->provider->street ?? ''
            : old('street') ?? '';
        $this->city = is_null(old('city')) ? $this->provider->city ?? '' : old('city') ?? '';
        $this->number = is_null(old('number'))
            ? $this->provider->number ?? ''
            : old('number') ?? '';
        $this->state = is_null(old('state')) ? $this->provider->state ?? '' : old('state') ?? '';
        $this->complement = is_null(old('complement'))
            ? $this->provider->complement ?? ''
            : old('complement') ?? '';
        $this->neighborhood = is_null(old('neighborhood'))
            ? $this->provider->neighborhood ?? ''
            : old('neighborhood') ?? '';

        $this->provider_id = is_null(old('id')) ? $this->provider->id ?? '' : old('id');

        $this->blockedPeriods = $this->provider->blockedPeriods;
    }

    public function updatedCpfCnpj($newValue)
    {
        $cpfCnpj = new CpfCnpj($newValue);

        if ($cpfCnpj->valida()) {
            $this->resetErrorBag('cpfcnpj');
        } else {
            $this->addError('cpfcnpj', 'CPF/CNPJ inválido');
            $this->focus('cpfcnpj');
        }
    }

    function updatedZipcode($newValue)
    {
        try {
            if ($result = app(Zipcode::class)->get(only_numbers($newValue))) {
                $this->street = $result['logradouro'];
                $this->city = $result['localidade'];
                $this->state = $result['uf'];
                $this->neighborhood = $result['bairro'];

                $this->focus('number');

                $this->resetErrorBag('zipcode');
            } else {
                $this->focus('zipcode');

                $this->addError('zipcode', 'CEP não encontrado');
            }
        } catch (\Exception $e) {
            $this->focus('zipcode');
            info('Exception no CEP');
        }
    }

    protected function getComponentVariables()
    {
        return [
            'provider' => $this->provider->load('blockedPeriods'),
        ];
    }

    public function mount()
    {
        if ($this->mode == 'create') {
            $this->provider = new Provider();
        }

        $this->fillModel();
    }

    public function render()
    {
        return view('livewire.providers.form')->with($this->getViewVariables());
    }
}
