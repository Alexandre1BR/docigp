<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClosedStatusToBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('congressman_budgets', function (Blueprint $table) {
            $table
                ->bigInteger('closed_by_id')
                ->unsigned()
                ->nullable();

            $table->timestamp('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('congressman_budgets', function (Blueprint $table) {
            $table->dropColumn('closed_by_id');

            $table->dropColumn('closed_at');
        });
    }
}
