<?php

use App\Http\Controllers\Web\Admin\CongressmanLegislatures as CongressmanLegislatures;

Route::group(['prefix' => '/congressman-legislatures'], function () {
    Route::post(
        '/remove',
        [CongressmanLegislatures::class,'removeFromLegislature']
    )->name('congressman-legislatures.remove-from-legislature');

    Route::post(
        '/include',
        [CongressmanLegislatures::class,'includeInLegislature']
    )->name('congressman-legislatures.include-in-legislature');
});
