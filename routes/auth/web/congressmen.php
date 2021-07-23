<?php

use App\Http\Controllers\Web\Admin\Congressmen as Congressmen;

Route::group(['prefix' => '/congressmen'], function () {
    Route::get('/', [Congressmen::class,'index'])->name('congressmen.index');

    Route::get('/create', [Congressmen::class,'create'])->name('congressmen.create');

    Route::post('/', [Congressmen::class,'associateWithUser'])->name(
        'congressmen.associate-with-user'
    );

    Route::get('/{id}', [Congressmen::class,'show'])->name('congressmen.show');
});
