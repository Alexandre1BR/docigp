<?php

use App\Data\Models\Congressman;
use App\Data\Repositories\Parties;
use Illuminate\Database\Eloquent\Factory;

//$factory->define(Congressman::class, function () {
//    return [
//        'name' => faker()->name,
//        'nickname' => faker()->name,
//        'remote_id' => '999',
//        'party_id' => app(Parties::class)->randomElement()->id,
//    ];
//});


class CongressmanFactory extends Factory{


    protected $model = Congressman::class;


    public function definition()
    {
            return [
        'name' => faker()->name,
        'nickname' => faker()->name,
        'remote_id' => '999',
        'party_id' => app(Parties::class)->randomElement()->id,
        ];
    }

}
