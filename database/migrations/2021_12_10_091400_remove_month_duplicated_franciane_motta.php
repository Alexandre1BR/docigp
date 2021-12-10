<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMonthDuplicatedFrancianeMotta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $toBeDeletedCongressmanBudgets = [
            2339
        ];

        foreach ($toBeDeletedCongressmanBudgets as $id) {
            $count = DB::delete("delete from entries where congressman_budget_id={$id}");
            dump('Removendo as entries ' . $count);
            $count = DB::delete("delete from congressman_budgets where id={$id}");
            dump('Removendo o congressman_budgets ' . $count);
        }
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
