<?php

Route::group(['prefix' => '/annual-report'], function () {
    Route::post('/', 'AnnualReport@generate')->name('annual-reports.generate');
    Route::get('/', 'AnnualReport@index')->name('annual-reports.index');
    Route::post('/generateGeneral', 'AnnualReport@generateGeneral')->name('general-annual-reports.generate');
});
