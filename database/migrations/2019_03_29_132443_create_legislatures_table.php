<?php

use App\Models\Legislature;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLegislaturesTable extends Migration
{
    private function populate()
    {
        Legislature::create([
            'number' => 11,
            'year_start' => 2019,
            'year_end' => 2023,
            'created_by_id' => 1, // system
        ]);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legislatures', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('number');
            $table->integer('year_start');
            $table->integer('year_end');

            $table
                ->bigInteger('created_by_id')
                ->unsigned()
                ->nullable();

            $table
                ->bigInteger('updated_by_id')
                ->unsigned()
                ->nullable();

            $table->timestamps();
        });

        $this->populate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legislatures');
    }
}
