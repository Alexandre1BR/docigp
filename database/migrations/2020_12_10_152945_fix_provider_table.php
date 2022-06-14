<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if($provider = \App\Models\Provider::find(661)){
            $provider->delete();
            dump('O Provider 661 foi excluído');
        }else{
            dump('Não foi encontrado o provider 661');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //there's no fallback
    }
}
