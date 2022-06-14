<?php

namespace Database\Factories;


use Carbon\Carbon;
use App\Models\Congressman;
use App\Data\Repositories\Legislatures;
use App\Models\CongressmanLegislature;
use Illuminate\Database\Eloquent\Factories\Factory;

//$factory->define(CongressmanLegislature::class, function () {
//    return [
//        'congressman_id' => factory(Congressman::class)->create()->id,
//        'legislature_id' => app(Legislatures::class)->randomElement()->id,
//        'started_at' => Carbon::parse('2019-02-01'),
//    ];
//});

class CongressmanLegislatureFactory extends Factory{

    protected $model = CongressmanLegislature::class;

    public function definition()
    {
            return [
        'congressman_id' => Congressman::factory()->create()->id,
        'legislature_id' => app(Legislatures::class)->randomElement()->id,
        'started_at' => Carbon::parse('2019-02-01'),
        ];
    }
}
