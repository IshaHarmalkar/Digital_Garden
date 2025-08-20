<?php

namespace App\Mail;

use App\Models\Native;
use App\Models\NotionContent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Notion;

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
        $this->items = Native::where('created_at', '>=', now()->subWeek())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // notion content
        $notionContents = NotionContent::inRandomOrder()->limit(2)->get();

        $this->notionPages = $notionContents->map(function ($content) {
            $page = Notion::pages()->find($content->notion_page_id);

            return [
                'title' => $content->title,
                'url' => $page->getUrl(),
            ];
        });

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
