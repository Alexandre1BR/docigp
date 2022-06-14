<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');

            $table->string('initials')->nullable();

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

        Schema::table('users', function (Blueprint $table) {
            $table
                ->bigInteger('department_id')
                ->unsigned()
                ->nullable();
        });

        Schema::table('bouncer_roles', function (Blueprint $table) {
            $table
                ->bigInteger('department_id')
                ->unsigned()
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });

        Schema::table('bouncer_roles', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });
    }
}
