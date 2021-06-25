<?php

use App\Models\EntryType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryTypesTable extends Migration
{
    private function populate()
    {
        EntryType::insert([
            ['name' => 'Depósito Alerj', 'document_number_required' => false],
            ['name' => 'Transporte', 'document_number_required' => false],
            ['name' => 'Transferência', 'document_number_required' => false],
            ['name' => 'Cheque', 'document_number_required' => true],
            ['name' => 'Débito', 'document_number_required' => false],
            ['name' => 'Débito em conta', 'document_number_required' => false],
        ]);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_types', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');

            $table->boolean('document_number_required')->default(false);

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
        Schema::dropIfExists('entry_types');
    }
}
