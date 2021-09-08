<?php

use App\Http\Controllers\Web\Admin\AnnualReport as AnnualReports;

Route::group(['prefix' => '/annual-report'], function () {
    Route::post('/', [AnnualReports::class,'generate'])->name('annual-reports.generate');
    Route::get('/', [AnnualReports::class,'index'])->name('annual-reports.index');
    Route::post('/generateGeneral', [AnnualReports::class,'generateGeneral'])->name('general-annual-reports.generate');
});
