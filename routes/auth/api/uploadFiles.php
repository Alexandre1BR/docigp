<?php

use App\Http\Controllers\Api\UploadFiles as UploadFiles;

Route::group(['prefix' => '/upload-files'], function () {
    Route::get('/', [UploadFiles::class,'index'])->name('upload-files.index');

    Route::get('/create', [UploadFiles::class,'create'])->name('upload-files.create');

    Route::post('/', [UploadFiles::class,'store'])->name('upload-files.store');
});
