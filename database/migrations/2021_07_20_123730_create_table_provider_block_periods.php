<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Provider as ProviderModel;
use App\Models\ProviderBlockPeriod as ProviderBlockPeriodModel;
use Carbon\Carbon;

class CreateTableProviderBlockPeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_block_periods', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('provider_id')->unsigned();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();

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

        foreach (ProviderModel::cursor() as $provider) {
            if ($provider->is_blocked) {
                $period = new ProviderBlockPeriodModel();
                $period->provider_id = $provider->id;
                $period->start_date = Carbon::createFromFormat('Y-m-d', '2018-01-01')->startOfDay();
                $period->save();
            }
        }

        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });

        foreach (ProviderModel::cursor() as $provider) {
            $provider->is_blocked =
                ProviderBlockPeriodModel::where('start_date', '<=', now())
                    ->where(function ($query) {
                        $query->orWhereNull('end_date')->orWhere('end_date', '>', now());
                    })
                    ->where('provider_id', $provider->id)
                    ->count() > 0;

            $provider->save();
        }

        Schema::dropIfExists('provider_block_periods');
    }
}
