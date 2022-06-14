<?php
Route::group(['prefix' => '/api/v1'], function () {
    require __DIR__ . '/api/environment.php';
    require __DIR__ . '/api/congressmen.php';
    require __DIR__ . '/api/budgets.php';
    require __DIR__ . '/api/costCenters.php';
    require __DIR__ . '/api/cpfCnpj.php';
    require __DIR__ . '/api/entryTypes.php';
    require __DIR__ . '/api/users.php';
});
