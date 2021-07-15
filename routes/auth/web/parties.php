<?php

use App\Http\Controllers\Web\Admin\Parties as Parties;

Route::group(['prefix' => '/parties'], function () {
    Route::get('/create', [Parties::class,'create'])->name('parties.create');

    Route::get('/', [Parties::class,'index'])->name('parties.index');

    Route::get('/{id}', [Parties::class,'show'])->name('parties.show');

    Route::post('/{id}', [Parties::class,'update'])->name('parties.update');

    Route::post('/', [Parties::class,'store'])->name('parties.store');
});
