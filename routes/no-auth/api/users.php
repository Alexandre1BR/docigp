<?php

use App\Http\Controllers\Api\Users;

Route::group(['prefix' => '/users'], function () {
    Route::post('/per-page/{size}', [Users::class,'perPage'])->name('users.per-page');
});
