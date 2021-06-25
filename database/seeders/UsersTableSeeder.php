<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User as UserModel;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        UserModel::disableEvents();

        factory(UserModel::class, 50)->create();
    }
}
