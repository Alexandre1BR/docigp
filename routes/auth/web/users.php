<?php

use App\Http\Controllers\Web\Admin\Users as Users;

Route::group(['prefix' => '/users'], function () {
    Route::get('/', [Users::class,'index'])->name('users.index');

    Route::get('/show/{id}', [Users::class,'show'])->name('users.show');

    Route::get('/create', [Users::class,'create'])->name('users.create');

    Route::post('/{id}', [Users::class,'update'])->name('users.update');

    Route::post('/', [Users::class,'store'])->name('users.store');
});
