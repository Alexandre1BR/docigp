<?php

use App\Http\Controllers\Web\Admin\AnnualReport as AnnualReports;
use App\Http\Controllers\Web\Admin\AnnualReport as AnnualReport;

Route::group(['prefix' => '/annual-report'], function () {
    Route::post('/', [AnnualReport::class,'generate'])->name('annual-reports.generate');
    Route::get('/', [AnnualReport::class,'index'])->name('annual-reports.index');
    Route::post('/generateGeneral', [AnnualReport::class,'generateGeneral'])->name('general-annual-reports.generate');
});
