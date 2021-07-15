<?php

use App\Http\Controllers\Web\Admin\Providers as Providers;
use App\Http\Livewire\Providers\UpdateForm as ProviderUpdateForm;
use App\Http\Livewire\Providers\CreateForm as ProviderCreateForm;

Route::group(['prefix' => '/providers'], function () {
    //    Route::get('/create', [Providers::class, 'create'])->name('providers.create');
    Route::get('/create', ProviderCreateForm::class)->name('providers.create');

    Route::post('/', [Providers::class, 'store'])->name('providers.store');

    Route::get('/{provider}', ProviderUpdateForm::class)->name('providers.show');

    Route::post('/{id}', [Providers::class, 'update'])->name('providers.update');

    Route::get('/', [Providers::class, 'index'])->name('providers.index');
});
