<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Data\Repositories\Users as UsersRepository;

class UpdateAdministrators extends Migration
{
    const EMAILS = [
        'afaria@alerj.rj.gov.br',
        'ovalenca@alerj.rj.gov.br',
        'afdsilva@alerj.rj.gov.br',
        'blaignier@alerj.rj.gov.br',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usersRepository = app(UsersRepository::class);

        $bmasquio = $usersRepository->findByEmail('bmasqui@alerj.rj.gov.br');
        $bmasquio->email = 'bmasquio@alerj.rj.gov.br';
        $bmasquio->save();
        $bmasquio->assign('administrator');

        collect(static::EMAILS)->each(function ($item) use ($usersRepository) {
            $user = $usersRepository->findByEmail($item);
            $user->assign('administrator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $usersRepository = app(UsersRepository::class);

        $bmasquio = $usersRepository->findByEmail('bmasquio@alerj.rj.gov.br');
        $bmasquio->email = 'bmasqui@alerj.rj.gov.br';
        $bmasquio->save();
        $bmasquio->retract('administrator');

        collect(static::EMAILS)->each(function ($item) use ($usersRepository) {
            $user = $usersRepository->findByEmail($item);
            $user->retract('administrator');
        });
    }
}
