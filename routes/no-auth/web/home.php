<?php

use App\Http\Controllers\Web\Pub\Home as Home;

Route::get('/', [Home::class,'index'])->name('home');

Route::get('/', [Home::class,'index'])->name('home.index');

Route::get('/transparencia', [Home::class,'transparency'])->name('home.transparency');
