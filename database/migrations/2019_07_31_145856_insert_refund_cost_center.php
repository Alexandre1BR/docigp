<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Data\Repositories\CostCenters as CostCentersRepository;
use App\Models\CostCenter as CostCentersModel;
use App\Support\Constants;

class InsertRefundCostCenter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $costCenter = new CostCentersModel();
        $costCenter->code = 4;
        $costCenter->name = 'Devolução de saldo';
        $costCenter->created_by_id = Constants::ALERJ_PROVIDER_ID;
        $costCenter->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app(CostCentersRepository::class)
            ->findByCode(4)
            ->delete();
    }
}
