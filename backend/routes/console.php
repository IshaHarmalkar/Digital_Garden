<?php

use App\Mail\WeeklyNewsletterMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::call(function()
// {

//         Mail::to('example@email.com')->send(new WeeklyNewsletterMail());

// })->weeklyOn(7, '9:00');

Schedule::call(function () {

    Mail::to('example@email.com')->send(new WeeklyNewsletterMail);

})->everyMinute();
