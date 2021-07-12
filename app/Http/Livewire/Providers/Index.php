<?php

namespace App\Http\Livewire\Providers;

use App\Data\Repositories\Providers as ProvidersRepository;
use App\Http\Livewire\BaseIndex;

class Index extends BaseIndex
{
    protected $repository = ProvidersRepository::class;
    public $isBlocked = false;
    public $pageSize = 20;
    protected $refreshFields = ['isBlocked', 'searchString'];
    public $searchFields = [
        'providers.name' => 'text',
        'providers.cpf_cnpj' => 'text',
        'providers.zipcode' => 'text',
        'providers.street' => 'text',
        'providers.number' => 'text',
        'providers.complement' => 'text',
        'providers.neighborhood' => 'text',
        'providers.city' => 'text',
        'providers.state' => 'text',
    ];

    public function additionalFilterQuery($query)
    {
        return $query->when($this->isBlocked, function ($query, $isBlocked) {
            return $query->where('is_blocked', $isBlocked);
        });
    }

    public function render()
    {
        return view('livewire.providers.index')->with([
            'providers' => $this->filter(),
        ]);
    }
}
