<?php

use App\Http\Controllers\Api\Budgets;

Route::group(['prefix' => '/budgets'], function () {
    Route::post('/{id}', [Budgets::class,'update'])->name('budgets.update');

    Route::post('/', [Budgets::class,'store'])->name('budgets.store');
});
