<?php

namespace Database\Factories;

use App\Data\Repositories\Providers;
use App\Data\Repositories\Users as UsersRepository;
use App\Models\ProviderBlockPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderBlockPeriodFactory extends Factory{

    protected $model = ProviderBlockPeriod::class;

    public function definition()
    {

        return [
        'provider_id' => app(Providers::class)->randomElement()->id,

        'start_date' => faker()->dateTimeBetween('-1 month','-1 week'),

        'end_date' => faker()->dateTimeBetween('+1 month','+2 months'),

        'created_by_id' => app(UsersRepository::class)->randomElement()->id,

        'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
        
        'created_at' => now(),

        'updated_at' => now(),
        ];
    }
}
