<?php

use App\Mail\WeeklyNewsletterMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $recipient = env('NEWSLETTER_RECIPIENT');
    Mail::to($recipient)->send(new WeeklyNewsletterMail);

})->weeklyOn(7, '10:00');

Schedule::command('notion:sync '.config('services.notion.database_id'))
    ->weeklyOn(7, '9:00') // Sundays at 2 AM
    ->withoutOverlapping();

Schedule::command('pinterest:sync')
    ->weeklyOn(7, '11:00') // Sundays 11 AM
    ->withoutOverlapping();
