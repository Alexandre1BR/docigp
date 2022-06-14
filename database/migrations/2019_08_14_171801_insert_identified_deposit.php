<?php

use App\Data\Repositories\EntryTypes as EntryTypesRepository;
use Illuminate\Database\Migrations\Migration;
use App\Models\EntryType;
use App\Support\Constants;

class InsertIdentifiedDeposit extends Migration
{
    protected $entryType = [
        'name' => 'Depósito identificado',
        'document_number_required' => true,
        'created_by_id' => Constants::ALERJ_PROVIDER_ID,
        'updated_by_id' => Constants::ALERJ_PROVIDER_ID,
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $entryType = new EntryType();

        foreach ($this->entryType as $key => $value) {
            $entryType->{$key} = $value;
        }

        $entryType->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app(EntryTypesRepository::class)
            ->findByName($this->entryType['name'])
            ->delete();
    }
}
