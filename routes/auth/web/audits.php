<?php

use App\Http\Controllers\Web\Admin\Audits as Audits;
use App\Http\Controllers\Web\Admin\Users as Users;

Route::group(['prefix' => '/audits', 'middleware' => ['can:audits:show']], function () {
    Route::get('/', [Audits::class, 'index'])->name('audits.index');
    Route::get('/export', [Users::class, 'export'])->name('audits.export');
});
