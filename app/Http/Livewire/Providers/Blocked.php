<?php

namespace App\Http\Livewire\Providers;

use App\Data\Repositories\Providers as ProvidersRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Blocked extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $searchString = '';
    public $isBlocked = false;

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

    public function updatingSearchString()
    {
        $this->resetPage();
    }

    public function additionalFilterQuery($query)
    {
        return $query->where('is_blocked', true);
    }

    public function filter()
    {
        $query = app(ProvidersRepository::class)
            ->newQuery()
            ->where(function ($query) {
                collect($this->searchFields)->each(function ($key, $field) use ($query) {
                    switch ($key) {
                        case 'text':
                            $query->orWhere($field, 'ilike', "%{$this->searchString}%");
                            break;
                    }
                });
            });

        $query = $this->additionalFilterQuery($query);

        return $query->paginate($this->pageSize);
    }

    public function render()
    {
        return view('livewire.providers.blocked')->with([
            'providers' => $this->filter(),
        ]);
    }
}
