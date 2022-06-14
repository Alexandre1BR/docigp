<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCongressmanBudgetsRenameCollunsTransportDebitEntryIdAndTransportCreditEntryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('congressman_budgets', function (Blueprint $table) {
            $table->renameColumn('transport_debit_entry_id', 'transport_from_previous_entry_id');
        });

        Schema::table('congressman_budgets', function (Blueprint $table) {
            $table->renameColumn('transport_credit_entry_id', 'transport_to_next_entry_id');
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
            $table->renameColumn('transport_from_previous_entry_id','transport_debit_entry_id');
        });

        Schema::table('congressman_budgets', function (Blueprint $table) {
            $table->renameColumn('transport_to_next_entry_id', 'transport_credit_entry_id');
        });


    }
}
