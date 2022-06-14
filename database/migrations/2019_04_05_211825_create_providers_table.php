<?php

use App\Models\Provider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    private function populate()
    {
        Provider::create([
            'cpf_cnpj' => '30.449.862/0001-67',

            'type' => 'PJ',

            'name' => 'Assembleia Legislativa do Estado do Rio de Janeiro',

            'created_by_id' => 1,

            'updated_by_id' => 1,
        ]);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('cpf_cnpj')->index();

            $table->string('type');

            $table->string('name');

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
        Schema::dropIfExists('providers');
    }
}
