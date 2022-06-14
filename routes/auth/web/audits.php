<?php

use App\Http\Controllers\Web\Admin\Users as Users;
use App\Http\Livewire\Audits\Index as AuditsIndex;

Route::group(['prefix' => '/audits', 'middleware' => ['can:audits:all-show']], function () {
    Route::get('/', AuditsIndex::class)->name('audits.index');
    Route::get('/export', [Users::class, 'export'])->name('audits.export');
});
