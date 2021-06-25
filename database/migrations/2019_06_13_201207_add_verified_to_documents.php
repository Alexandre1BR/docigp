<?php

use App\Models\EntryDocument;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerifiedToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry_documents', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable();

            $table
                ->bigInteger('verified_by_id')
                ->unsigned()
                ->nullable();
        });

        EntryDocument::whereNotNull('analysed_at')->update([
            'verified_at' => DB::raw('analysed_at'),
            'verified_by_id' => DB::raw('analysed_by_id'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entry_documents', function (Blueprint $table) {
            $table->dropColumn('verified_at');

            $table->dropColumn('verified_by_id');
        });
    }
}
