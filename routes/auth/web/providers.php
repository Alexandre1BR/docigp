<?php

use App\Http\Controllers\Web\Admin\Providers as Providers;

Route::group(['prefix' => '/providers'], function () {
    Route::get('/create', [Providers::class, 'create'])->name('providers.create');

    Route::post('/', [Providers::class, 'store'])->name('providers.store');

    Route::get('/{id}', [Providers::class, 'show'])->name('providers.show');

    Route::post('/{id}', [Providers::class, 'update'])->name('providers.update');

    Route::get('/', [Providers::class, 'index'])->name('providers.index');
});
