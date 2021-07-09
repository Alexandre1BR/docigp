<?php

use App\Http\Controllers\Web\Admin\EntryTypes as EntryTypes;

Route::group(['prefix' => '/entry-types'], function () {
    Route::get('/create', [EntryTypes::class,'create'])->name('entry-types.create');

    Route::post('/', [EntryTypes::class,'store'])->name('entry-types.store');

    Route::get('/{id}', [EntryTypes::class,'show'])->name('entry-types.show');

    Route::post('/{id}', [EntryTypes::class,'update'])->name('entry-types.update');

    Route::get('/', [EntryTypes::class,'index'])->name('entry-types.index');
});
