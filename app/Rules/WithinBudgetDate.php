<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Data\Repositories\CongressmanBudgets;

class WithinBudgetDate implements Rule
{
    private $congressmanBudgetId;

    private $date;

    /**
     * Create a new rule instance.
     *
     * @param $congressmanBudgetId
     */
    public function __construct(int $congressmanBudgetId)
    {
        $this->congressmanBudgetId = $congressmanBudgetId;
    }

    /**
     * @return string
     */
    private function getMonth()
    {
        return $this->date->format('m/Y');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $congressmanBbudget = app(CongressmanBudgets::class)->findById($this->congressmanBudgetId);

        $this->date = $value;

        return $value->year === $congressmanBbudget->budget->date->year &&
            $value->month === $congressmanBbudget->budget->date->month;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mês do lançamento (' . $this->getMonth() . ') está incorreto.';
    }
}
