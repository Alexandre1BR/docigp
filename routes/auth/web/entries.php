<?php

use App\Http\Controllers\Web\Admin\Entries as Entries;

Route::group(['prefix' => '/entries'], function () {
    Route::get('/', [Entries::class,'index'])->name('entries.index');
});
