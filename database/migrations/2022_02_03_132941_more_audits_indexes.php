<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoreAuditsIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('audits');

            if (
                $doctrineTable->hasIndex($this->createIndexName(['auditable_type', 'auditable_id']))
            ) {
                $table->dropIndex(['auditable_type', 'auditable_id']);
            }

            if (
                !$doctrineTable->hasIndex(
                    $this->createIndexName(['auditable_id', 'auditable_type'])
                )
            ) {
                $table->index(['auditable_id', 'auditable_type']);
            }
        });
    }

    protected function createIndexName(array $columns)
    {
        $index = strtolower('audits_' . implode('_', $columns) . '_index');

        return str_replace(['-', '.'], '_', $index);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('audits');

            if (
                $doctrineTable->hasIndex($this->createIndexName(['auditable_id', 'auditable_type']))
            ) {
                $table->dropIndex(['auditable_id', 'auditable_type']);
            }
        });
    }
}
