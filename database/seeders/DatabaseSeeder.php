<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        config(['broadcasting.default' => 'null']);

//        $this->call(UsersTableSeeder::class);

//        $this->call(CongressmanTableSeeder::class);

//        $this->call(ProvidersTableSeeder::class);

        $this->call(EntriesTableSeeder::class);
    }
}
