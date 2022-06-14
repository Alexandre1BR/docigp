<?php

use App\Http\Controllers\Web\Admin\CostCenters;

Route::group(['prefix' => '/cost-centers'], function () {
    Route::get('/create', [CostCenters::class,'create'])->name('cost-centers.create');

    Route::post('/', [CostCenters::class,'store'])->name('cost-centers.store');

    Route::get('/{id}', [CostCenters::class,'show'])->name('cost-centers.show');

    Route::post('/{id}', [CostCenters::class,'update'])->name('cost-centers.update');

    Route::get('/', [CostCenters::class,'index'])->name('cost-centers.index');
});
