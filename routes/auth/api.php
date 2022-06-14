<?php

Route::group(
    [
        'prefix' => '/api/v1',
       // 'namespace' => 'Api',
        'middleware' => ['auth', 'app.users'],
    ],
    function () {
        require __DIR__ . '/api/congressmen.php';
        require __DIR__ . '/api/budgets.php';
        require __DIR__ . '/api/uploadFiles.php';
    }
);
