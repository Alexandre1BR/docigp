<?php

use App\Http\Controllers\Api\Environment;

Route::get('/environment', [Environment::class,'data']);
