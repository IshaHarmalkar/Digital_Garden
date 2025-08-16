<?php

use App\Mail\WeeklyNewsletterMail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/preview/newsletter', function () {
    return (new WeeklyNewsletterMail)->render();
});

require __DIR__.'/auth.php';
