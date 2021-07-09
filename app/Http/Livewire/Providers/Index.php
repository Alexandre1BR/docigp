<?php

namespace App\Http\Livewire\Providers;

use App\Data\Repositories\Providers as ProvidersRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $searchString = '';
    public $isBlocked = false;

    public $pageSize = 20;

    public function updatingSearchString()
    {
        $this->resetPage();
    }

    public function filter()
    {
        return app(ProvidersRepository::class)
            ->newQuery()
            ->where(function ($query) {
                $query
                    ->where('name', 'ilike', "%{$this->searchString}%")
                    ->orWhere('cpf_cnpj', 'ilike', "%{$this->searchString}%");
            })
            ->when($this->isBlocked, function ($query, $isBlocked) {
                return $query->where('is_blocked', $isBlocked);
            })
            ->paginate($this->pageSize);
    }

    public function render()
    {
        return view('livewire.providers.index')->with([
            'providers' => $this->filter(),
        ]);
    }
}
