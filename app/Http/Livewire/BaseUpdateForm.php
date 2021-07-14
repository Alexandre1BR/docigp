<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseUpdateForm extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $mode = 'update';

    protected $formVariables = ['mode'];
    protected function formVariables()
    {
        $array = [];
        collect($this->formVariables)->each(function ($field) use (&$array) {
            $array[$field] = $this->{$field};
        });

        return $array;
    }

    protected function focus($ref)
    {
        $this->dispatchBrowserEvent('focus-field', ['field' => $ref]);
    }

    protected function getComponentVariables()
    {
        return [];
    }

    protected function getViewVariables()
    {
        return $this->formVariables() + $this->getComponentVariables();
    }
}
