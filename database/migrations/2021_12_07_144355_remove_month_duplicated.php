<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMonthDuplicated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $count = DB::delete('delete from entries where congressman_budget_id=2359');
        dump('Removendo as entries ' . $count);
        $count = DB::delete('delete from congressman_budgets where id=2359');
        dump('Removendo o congressman_budgets ' . $count);
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
