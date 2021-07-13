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
    public $isEditing = false;
    protected function formVariables()
    {
        $array = [];
        collect($this->formVariables)->each(function ($field) use (&$array) {
            $array[$field] = $this->{$field};
        });

        return $array;
    }

    function edit()
    {
        $this->isEditing = !$this->isEditing;
        $this->resetPage();
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
