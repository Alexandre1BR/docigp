<?php

use Illuminate\Database\Migrations\Migration;

class FixAttachedFilesFileableType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('attached_files')
            ->where('fileable_type', 'App\Data\Models\EntryDocument')
            ->update(['fileable_type' => 'App\Models\EntryDocument']);
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
