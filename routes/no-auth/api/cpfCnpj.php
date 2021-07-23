<?php

use App\Http\Controllers\Api\CpfCnpj;

Route::group(['prefix' => '/cpf-cnpj'], function () {
    Route::post('/check', [CpfCnpj::class,'check'])->name('cpf-cnpj.check');
});
