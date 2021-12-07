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
        $toBeDeletedCongressmanBudgets = [
            2381,
            2329,
            2331,
            2332,
            2336,
            2340,
            2341,
            2345,
            2386,
            2349,
            2351,
            2354,
            2359,
            2362,
            2366,
            2371,
            2374,
            2397,
            2380,
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
