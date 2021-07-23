<?php
Route::group(
    ['prefix' => '/', 'middleware' => 'guest'],
    function () {
        require __DIR__ . '/web/home.php';
    }
);
