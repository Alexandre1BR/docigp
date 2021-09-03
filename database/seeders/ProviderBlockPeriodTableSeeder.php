<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\ProviderBlockPeriod;
use Faker\Factory;
use Illuminate\Database\Seeder;


class ProviderBlockPeriodTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        ProviderBlockPeriod::factory()->count(2)->create();
    }
}
