<?php

use Illuminate\Database\Migrations\Migration;

class FixBouncerEntityType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('bouncer_assigned_roles')
            ->where('entity_type', 'App\Data\Models\User')
            ->update(['entity_type' => 'App\Models\User']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Can't go back
    }
}
