<?php

use App\Http\Controllers\Api\Budgets;

Route::group(['prefix' => '/budgets'], function () {
    Route::get('/', [Budgets::class,'all'])->name('budgets.all');

    Route::get('/availableBudgets', [Budgets::class,'availableBudgets'])->name(
        'budgets.availableBudgets'
    );
});
