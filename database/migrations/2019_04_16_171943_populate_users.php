<?php

use App\Models\User;
use App\Data\Repositories\Users;
use Illuminate\Database\Migrations\Migration;

class PopulateUsers extends Migration
{
    const USERS = [
        'afaria@alerj.rj.gov.br' => 'Antonio Carlos Ribeiro',
        'ovalenca@alerj.rj.gov.br' => 'Orlando Vinícios Valença',
        'bmasqui@alerj.rj.gov.br' => 'Bruno Masquio',
        'afdsilva@alerj.rj.gov.br' => 'Alexandre Ferreira',
        'blaignier@alerj.rj.gov.br' => 'Breno Laignier',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        collect(static::USERS)->each(function ($name, $email) {
            User::disableEvents();

            app(Users::class)->firstOrCreate([
                'email' => $email,
                'name' => $name,
                'username' => $email,
                'password' => 'empty-password',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
