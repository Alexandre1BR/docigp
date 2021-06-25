<?php
use App\Support\Constants;
use App\Data\Models\User;
use App\Data\Repositories\Users as UsersRepository;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;
//
//$factory->define(User::class, function () {
//    do {
//        preg_match('/(.*?)@(.*)/', faker()->unique()->safeEmail, $output_array);
//
//        $username = $output_array[1];
//        $email = $output_array[1] . '@alerj.rj.gov.br';
//    } while (app(UsersRepository::class)->findByEmail($email));
//
//    return [
//        'name' => faker()->name,
//        'username' => $username,
//        'email' => $email,
//        'email_verified_at' => now(),
//        'password' =>
//            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//        'remember_token' => Str::random(10)
//    ];
//});
//$factory->defineAs(User::class, Constants::ROLE_ADMINISTRATOR, function (
//    $faker
//) use ($factory) {
//    $user = $factory->create(User::class);
//    $user->assign(Constants::ROLE_ADMINISTRATOR);
//    return $user->toArray();
//});


class UserFactory extends Factory{

    protected $model = User::class;

    public function definition()
    {
            do {
        preg_match('/(.*?)@(.*)/', faker()->unique()->safeEmail, $output_array);

        $username = $output_array[1];
        $email = $output_array[1] . '@alerj.rj.gov.br';
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
