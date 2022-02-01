<?php

namespace Database\Factories;

use App\Data\Repositories\Users as UsersRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $username = faker()->unique()->username;
            $email = $username . '@alerj.rj.gov.br';
        } while (app(UsersRepository::class)->findByEmail($email));
        return [
            'name' => faker()->name,
            'username' => $username,
            'email' => $email,
            'email_verified_at' => now(),
            'password' =>
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ];
//});
//$factory->defineAs(User::class, Constants::ROLE_ADMINISTRATOR, function (
//    $faker
//) use ($factory) {
//    $user = $factory->create(User::class);
//    $user->assign(Constants::ROLE_ADMINISTRATOR);
//    return $user->toArray();
//}

    }
}
