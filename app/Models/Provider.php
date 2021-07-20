<?php

namespace App\Models;

use App\Services\CpfCnpj\CpfCnpj;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpf_cnpj',
        'type',
        'name',
        'created_by_id',
        'updated_by_id',
        'is_blocked',

        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
    ];

    protected $orderBy = ['name' => 'asc'];
    protected $appends = ['fullAddress'];

    protected $filterableColumns = ['cpf_cnpj', 'name'];

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->type = $this->type ?? CpfCnpj::type($this->cpf_cnpj);

        return parent::save();
    }

    public function entries()
    {
        return $this->hasMany(Entry::class)->orderBy('date', 'desc');
    }

    public function getFullAddressAttribute()
    {
        $fullAddress = $this->street;

        if ($this->number) {
            $fullAddress .= ', ' . $this->number;
        }

        if ($this->complement) {
            $fullAddress .= ', ' . $this->complement;
        }

        if ($this->neighbourhood) {
            $fullAddress .= ' - ' . $this->neighbourhood;
        }

        if ($this->city || $this->state) {
            $fullAddress .= '. ';
        }

        if ($this->city) {
            $fullAddress .= $this->city;
        }

        if ($this->state) {
            $fullAddress .= '/' . $this->state;
        }

        return $fullAddress;
    }

    public function isBlocked($reference = null)
    {
        $reference = $reference ?? now();

        return $this->blockedPeriods()
            ->where('start_date', '<=', $reference)
            ->where(function ($query) use ($reference) {
                $query->orWhereNull('end_date')->orWhere('end_date', '>', $reference);
            })
            ->count() > 0;
    }

    public function scopeIsBlocked($query, $reference = null)
    {
        $reference = $reference ?? now();

        return $query->whereExists(function ($query) use ($reference) {
            $query
                ->select(\DB::raw(1))
                ->from('provider_block_periods')
                ->whereRaw('provider_block_periods.provider_id = providers.id')
                ->where('provider_block_periods.start_date', '<=', $reference)
                ->where(function ($query) use ($reference) {
                    $query
                        ->orWhereNull('provider_block_periods.end_date')
                        ->orWhere('provider_block_periods.end_date', '>', $reference);
                });
        });
    }

    public function blockedPeriods()
    {
        return $this->hasMany(ProviderBlockPeriod::class);
    }
}
