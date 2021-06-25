<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

use App\Models\Provider as ProviderModel;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(ProviderModel::class, 30)->create();
    }
}
