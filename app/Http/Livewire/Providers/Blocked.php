<?php

namespace App\Http\Livewire\Providers;

use App\Data\Repositories\Providers as ProvidersRepository;
use App\Http\Livewire\BaseIndex;

class Blocked extends BaseIndex
{
    protected $orderByField = 'name';
    protected $orderByDirection = 'asc';

    protected $repository = ProvidersRepository::class;
    public $pageSize = 10;
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
        return $query->where('is_blocked', true);
    }

    public function render()
    {
        return view('livewire.providers.blocked')->with([
            'providers' => $this->filter(),
        ]);
    }
}
