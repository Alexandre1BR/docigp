<?php

Route::group(['prefix' => '/annual-report'], function () {
    Route::get('/', 'AnnualReport@index')->name('annual-report.index');
});
