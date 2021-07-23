<?php

namespace App\Data\Repositories;

use App\Models\Party;

class Parties extends Repository
{
    /**
     * @var string
     */
    protected $model = Party::class;

    public function sync($data)
    {
        coollect($data)->each(function ($party) {
            $this->firstOrCreate(
                [
                    'code' => $party['sigla'],
                ],
                [
                    'name' => $party['nome'],
                ]
            );
        });
    }
}
