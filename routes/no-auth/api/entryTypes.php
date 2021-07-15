<?php

use App\Http\Controllers\Api\EntryTypes;

Route::group(['prefix' => '/entry-types'], function () {
    Route::get('/', [EntryTypes::class,'all'])->name('entry-types.all');
});
