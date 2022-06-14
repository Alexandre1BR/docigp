<?php

use Silber\Bouncer\Database\Role;
use Illuminate\Database\Migrations\Migration;

class RenameDirectorRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::where('name', 'director')->update([
            'name' => 'aci',
            'title' => 'ACI',
        ]);

        Role::where('name', 'financial')->delete();

        Role::where('name', 'financier')->update([
            'name' => 'financial',
            'title' => 'Financeiro',
        ]);
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
