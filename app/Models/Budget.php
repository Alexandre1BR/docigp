<?php

namespace App\Models;

class Budget extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'date',
        'federal_value',
        'percentage',
        'value',
        'created_by_id',
        'updated_by_id',
    ];

    protected $dates = ['date', 'created_at', 'updated_at'];

    protected $orderBy = ['date' => 'desc'];

    protected $filterableColumns = ['date'];

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->fillValue();

        return parent::save();
    }

    protected function fillValue(): void
    {
        if ($this->percentageChanged()) {
            $this->value = ($this->federal_value * $this->percentage) / 100;
        }
    }

    protected function percentageChanged()
    {
        return blank($this->value) || ($this->isDirty('percentage') && !$this->isDirty('value'));
    }
}
