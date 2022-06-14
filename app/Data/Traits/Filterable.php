<?php

namespace App\Data\Traits;

trait Filterable
{
    public function getFilterableColumns()
    {
        return coollect(isset($this->filterableColumns) ? $this->filterableColumns : []);
    }
}
