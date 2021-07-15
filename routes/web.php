<?php

use App\Services\Routes;

Auth::routes();

/// No Auth
require __DIR__ . '/no-auth/api.php';
require __DIR__ . '/no-auth/web.php';

/// Auth
require __DIR__ . '/auth/api.php';
require __DIR__ . '/auth/admin.php';
