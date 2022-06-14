<?php

namespace Database\Seeders;

use App\Data\Repositories\Users as UsersRepository;
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

        for ($i = 0; $i < 50; $i++) {
            do {
                $username = faker()->unique()->username;
                $email = $username . '@alerj.rj.gov.br';
            } while (app(UsersRepository::class)->findByEmail($email));
            UserModel::factory()
                ->count(1)
                ->create(['username' => $username, 'name' => $username, 'email' => $email]);
        }
    }
}
