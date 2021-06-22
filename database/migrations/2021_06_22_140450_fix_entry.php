<?php

use App\Data\Repositories\Entries as EntriesRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // id = 16676

        if($entry = \App\Data\Models\Entry::withoutGlobalScopes()->find(16676)) {

            dump('atualizando o id '. $entry->id);
            $entry->published_at = null;
            $entry->published_by_id = null;
            $entry->analysed_at = null;
            $entry->analysed_by_id = null;
            $entry->verified_at = null;
            $entry->verified_by_id = null;
          
            $entry->save();

        }else{
            dump('Não foi possível encontrar a entry com o id solicitado');
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
