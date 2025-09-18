<?php

namespace App\Console\Commands;

use App\Mail\WeeklyNewsletterMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the weekly newsletter email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recipient = env('NEWSLETTER_RECIPIENT');

        if (! $recipient) {
            $this->error('No newsletter recipient defined in .env!');

            return 1;
        }

        Mail::to($recipient)->send(new WeeklyNewsletterMail);

        $this->info("Weekly newsletter sent to {$recipient}.");

        return 0;
    }
}
