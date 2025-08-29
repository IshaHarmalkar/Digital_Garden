<?php

namespace App\Mail;

use App\Models\Native;
use App\Models\NativeQueue;
use App\Models\Newsletter;
use App\Models\NotionContent;
use App\Models\NotionQueue;
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
        // get items
        $nativeItems = $this->getNativeItems();
        $notionItems = $this->getNotionItems();

        // merge and log
        $curated = $nativeItems->merge($notionItems);
        $this->saveNewsletter($curated);

        $this->items = $this->mapNativeItems($nativeItems ?? collect());
        $this->notionPages = $this->mapNotionPages($notionItems ?? collect());

    }

    private function getNativeItems()
    {
        $nativeItems = collect();

        $priority = NativeQueue::priority()->oldestInQueue()->first();
        if ($priority) {
            $nativeItems->push(['type' => 'Native', 'id' => $priority->native_id]);
            $priority->native->stats()->updateOrCreate(['native_id' => $priority->native_id], ['last_sent_at' => now()]);
            // pop
            $priority->delete();
        }

        $main = NativeQueue::main()->oldestInQueue()->first();
        if ($main) {
            $nativeItems->push(['type' => 'Native', 'id' => $main->native_id]);
            $main->native->stats()->updateOrCreate([], ['last_sent_at' => now()]);
            $main->delete();
        }

        return $nativeItems;

    }

    private function getNotionItems()
    {
        $notionItems = collect();
        $priority = NotionQueue::priority()->oldestInQueue()->first();
        if ($priority) {
            $notionItems->push(['type' => 'Notion', 'id' => $priority->notion_content_id]);
            $priority->notionContent->stats()->updateOrCreate([], ['last_sent_at' => now()]);
            $priority->delete();
        }

        $oldest = null;
        $newest = null;

        if ($notionItems->isEmpty()) {
            $oldest = NotionQueue::main()->oldestInQueue()->first();
            $newest = NotionQueue::main()->newestInQueue()->first();

            foreach ([$oldest, $newest] as $queueItem) {
                if ($queueItem) {
                    $notionItems->push(['type' => 'Notion', 'id' => $queueItem->notion_content_id]);
                    $queueItem->notionContent->stats()->updateOrCreate([], ['last_sent_at' => now()]);
                    $queueItem->delete();
                }
            }

        }

        return $notionItems;

    }

    private function saveNewsletter($curated)
    {
        // creare a single newsletter record with all curated items
        Newsletter::create([
            'curated_items' => $curated->toArray(),
        ]);
    }

    private function mapNotionPages($notionItems)
    {
        return collect($notionItems)->map(function ($item) {
            $content = NotionContent::find($item['id']);
            if (! $content) {
                return null;
            }

            $page = Notion::pages()->find($content->notion_page_id);

            return [
                'title' => $content->title,
                'url' => $page->getUrl(),
            ];
        })->filter(); // remove nulls
    }

    private function mapNativeItems($nativeItems)
    {

        return $nativeItems->map(function ($item) {
            return Native::find($item['id']);
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
