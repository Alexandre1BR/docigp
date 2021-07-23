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
}
