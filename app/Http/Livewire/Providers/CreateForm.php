<?php

namespace App\Http\Livewire\Providers;

use App\Http\Livewire\BaseForm;
use App\Models\Provider;
use App\Models\ProviderBlockPeriod;
use App\Services\CpfCnpj\CpfCnpj;
use App\Services\Zipcode\Service as Zipcode;
use Carbon\Carbon;

class CreateForm extends BaseForm
{
    public $start_date;
    public $end_date;
    public $provider_id;
    public $selectedId;

    protected $listeners = ['confirm-delete' => 'confirmDelete'];

    public function clearPeriod()
    {
        $this->start_date = null;
        $this->end_date = null;
        $this->selectedId = null;
        $this->resetErrorBag();
    }

    public function confirmDelete()
    {
        if ($this->selectedId) {
            ProviderBlockPeriod::find($this->selectedId)->delete();
        }

        $this->provider->refresh();
    }

    public function prepareForUpdate($id)
    {
        $this->selectedId = $id;
        $period = ProviderBlockPeriod::find($id);

        $this->start_date = $period->start_date->format('Y-m-d');
        $this->end_date = $period->end_date ? $period->end_date->format('Y-m-d') : null;
        $this->provider_id = $period->provider_id;

        $this->dispatchBrowserEvent('show-modal', [
            'target' => 'period-modal',
        ]);
    }

    public function prepareForDelete($id)
    {
        $this->selectedId = $id;
        $this->dispatchBrowserEvent('swal', [
            'title' => 'Quer mesmo apagar o registro?',
            'text' => 'Não será possível desfazer essa ação',
            'confirmEvent' => 'confirm-delete',
            'action' => 'delete',
        ]);
    }

    public function store()
    {
        $validatedData = $this->validate([
            'provider_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => $this->end_date ? 'date|after_or_equal:start_date' : '',
        ]);

        if ($validatedData['end_date'] == '') {
            $validatedData['end_date'] = null;
        } else {
            $validatedData['end_date'] = Carbon::create($validatedData['end_date'])->endOfDay();
        }

        if ($this->selectedId) {
            $row = ProviderBlockPeriod::find($this->selectedId);
            $row->fill($validatedData);
            $row->save();
        } else {
            ProviderBlockPeriod::create($validatedData);
        }

        $this->clearPeriod();
        $this->provider->refresh();
        $this->dispatchBrowserEvent('hide-modal', ['target' => 'period-modal']);
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
        return [];
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
