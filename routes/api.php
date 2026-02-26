<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return [
        'ok' => true,
        'service' => 'openpdf-api',
    ];
});
