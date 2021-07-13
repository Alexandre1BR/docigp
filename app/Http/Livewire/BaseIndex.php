<?php

namespace App\Http\Livewire;

use App\Data\Repositories\Providers as ProvidersRepository;
use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseIndex extends Component
{
    use WithPagination;

    protected $repository;
    protected $paginationTheme = 'bootstrap';
    public $searchString = '';
    public $pageSize = 20;
    protected $refreshFields = ['searchString'];

    public function updating($field)
    {
        collect($this->refreshFields)->each(function ($refreshField) use ($field) {
            if ($field == $refreshField) {
                $this->resetPage();
            }
        });
    }

    public $searchFields = [];

    public function additionalFilterQuery($query)
    {
        return $query;
    }

    protected $orderByField = 'updated_at';
    protected $orderByDirection = 'desc';
    public function orderBy($query)
    {
        return $query->orderBy($this->orderByField, $this->orderByDirection);
    }

    public function filter()
    {
        $query = app($this->repository)
            ->newQuery()
            ->where(function ($query) {
                collect($this->searchFields)->each(function ($key, $field) use ($query) {
                    switch ($key) {
                        case 'text':
                            $query->orWhereRaw(
                                'unaccent(' .
                                    $field .
                                    ") ILIKE '%'||unaccent('" .
                                    pg_escape_string($this->searchString) .
                                    "')||'%' "
                            );
                            break;
                    }
                });
            });

        $query = $this->additionalFilterQuery($query);

        $query = $this->orderBy($query);

        return $query->paginate($this->pageSize);
    }
}
