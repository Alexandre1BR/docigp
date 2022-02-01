<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexesForMostUsedSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('congressman_budgets', function (Blueprint $table) {
            $table->index('congressman_legislature_id');
        });

        Schema::table('congressman_legislatures', function (Blueprint $table) {
            $table->index(['congressman_id', 'legislature_id']);
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->index(['congressman_budget_id', 'date']);
            $table->index(['congressman_budget_id', 'cost_center_id']);
        });

        Schema::table('entry_comments', function (Blueprint $table) {
            $table->index('entry_id');
        });

        Schema::table('entry_documents', function (Blueprint $table) {
            $table->index('entry_id');
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
            $table->dropIndex(['congressman_legislature_id']);
        });

        Schema::table('congressman_legislatures', function (Blueprint $table) {
            $table->dropIndex(['congressman_id', 'legislature_id']);
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropIndex(['congressman_budget_id', 'date']);
            $table->dropIndex(['congressman_budget_id', 'cost_center_id']);
        });

        Schema::table('entry_comments', function (Blueprint $table) {
            $table->dropIndex(['entry_id']);
        });

        Schema::table('entry_documents', function (Blueprint $table) {
            $table->dropIndex(['entry_id']);
        });
    }
}
