<?php

namespace App\Mail;

use App\Models\Native;
use App\Models\NativeQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $items;

    public $notionPages;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->items = $this->curateNative();
        $this->notionPages = $this->curateNotion();
    }

    // Selecting Native Content
    private function curateNative()
    {
        $nativeItems = collect();

        // check priority Q
        $priority = NativeQueue::priority()->oldestInQueue()->first();
        if ($priority) {
            $content = $priority->native;
            $nativeItems->push($content);

            // Update Stats
            $content->stats()->updateOrCreate([], [
                'last_seen_at' => now(),
            ]);

            // log to newsletter

        }

        // main Native
        $main = NativeQueue::main()->oldestInQueue()->first();
        if ($main) {
            $content = $main->native;
            $nativeItems->push($content);

            $content->newsletters()->create();
            $content->stats()->updateOrCreate([], [
                'last_seen_at' => now(),
            ]);

            $main->delete();
        }

        return $nativeItems;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Weekly Review',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter',
            with: [
                'items' => $this->items,
                'notionPages' => $this->notionPages,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
