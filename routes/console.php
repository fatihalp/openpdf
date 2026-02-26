<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Build open tools for everyone.');
})->purpose('Display an inspiration quote');
