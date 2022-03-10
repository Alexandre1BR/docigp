<?php

namespace App\Rules;

use App\Data\Repositories\CostCenters as CostCentersRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class NotRevokedCostCenter implements Rule
{
    private $date;
    private $costCenter;

    /**
     * Create a new rule instance.
     *
     * @param $congressmanBudgetId
     */
    public function __construct($date)
    {
        $this->date = $date;
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
        $this->costCenter = app(CostCentersRepository::class)->findById($value);
        if ($this->costCenter->revoked_at) {
            $revoked_at = Carbon::create($this->costCenter->revoked_at);

            return $this->date < $revoked_at;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Este centro de custo foi revogado em ' . $this->costCenter->formatted_revoked_at;
    }
}
