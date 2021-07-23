<?php

use App\Http\Controllers\Web\Admin\Legislatures as Legislatures;

Route::group(['prefix' => '/legislatures'], function () {
    Route::get('/create', [Legislatures::class,'create'])->name('legislatures.create');

    Route::post('/', [Legislatures::class,'store'])->name('legislatures.store');

    Route::get('/{id}', [Legislatures::class,'show'])->name('legislatures.show');

    Route::post('/{id}', [Legislatures::class,'update'])->name('legislatures.update');

    Route::get('/', [Legislatures::class,'index'])->name('legislatures.index');
});
