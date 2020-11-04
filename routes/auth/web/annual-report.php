<?php

Route::group(['prefix' => '/annual-report'], function () {
    Route::get('/', 'AnnualReport@index')->name('annual-report.index');
    Route::get('/cost-center', 'AnnualReport@costCenterTable')->name(
        'annual-report.cost-center'
    );
});
