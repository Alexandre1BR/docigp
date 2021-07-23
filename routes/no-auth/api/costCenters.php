<?php

use App\Http\Controllers\Api\CostCenters;

Route::group(['prefix' => '/cost-centers'], function () {
    Route::get('/', [CostCenters::class,'all'])->name('cost-centers.all');
});
