<?php


Route::get('/', [\App\Http\Controllers\Web\Admin\Home::class,'index'])->name('admin.index');

Route::post('/change-client/', [\App\Http\Controllers\Web\Admin\Home::class,'changeClient'])->name('change.client');
