<?php

namespace App\Mail;

use App\Models\Native;
use App\Models\NativeQueue;
use App\Models\Newsletter;
use App\Models\NotionContent;
use App\Models\NotionQueue;
use App\Models\PinterestContent;
use App\Models\PinterestQueue;
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

    public $pins;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        // get items
        $nativeItems = $this->getNativeItems();
        $notionItems = $this->getNotionItems();
        $pinterestItems = $this->getPinterestItems();

        // merge and log
        $curated = $nativeItems->merge($notionItems)->merge($pinterestItems);
        $this->saveNewsletter($curated);

        $this->items = $this->mapNativeItems($nativeItems ?? collect());
        $this->notionPages = $this->mapNotionPages($notionItems ?? collect());
        $this->pins = $this->mapPinterestPins($pinterestItems ?? collect());

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

    private function getPinterestItems()
    {
        $pinterestItems = collect();

        $priority = PinterestQueue::priority()->oldestInQueue()->first();
        if ($priority) {
            $pinterestItems->push(['type' => 'Pinterest', 'id' => $priority->pinterest_content_id]);
            $priority->pinterestContent->stats()->updateOrCreate([], ['last_sent_at' => now()]);
            $priority->delete();
        }

        $oldest = null;
        $newest = null;

        if ($pinterestItems->isEmpty()) {
            $oldest = PinterestQueue::main()->oldestInQueue()->first();
            $newest = PinterestQueue::main()->newestInQueue()->first();

            foreach ([$oldest, $newest] as $queueItem) {
                if ($queueItem) {
                    $pinterestItems->push(['type' => 'Pinterest', 'id' => $queueItem->pinterest_content_id]);
                    $queueItem->pinterestContent->stats()->updateOrCreate([], ['last_sent_at' => now()]);
                    $queueItem->delete();
                }
            }
        }

        return $pinterestItems;
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
            $content = NotionContent::with('stats')->find($item['id']);
            if (! $content) {
                return null;
            }

            $page = Notion::pages()->find($content->notion_page_id);

            return [
                'id' => $content->id,
                'title' => $content->title,
                'url' => $page->getUrl(),
                'like_count' => $content->stats->like_count ?? 0,
                'see_again_soon' => $content->stats->see_again_soon ?? false,
            ];
        })->filter(); // remove nulls
    }

    private function mapNativeItems($nativeItems)
    {

        return $nativeItems->map(function ($item) {

            $native = Native::with('stats')->find($item['id']);

            return $native;

        });

    }

    private function mapPinterestPins($pinterestItems)
    {
        return collect($pinterestItems)->map(function ($item) {
            $pin = PinterestContent::with('stats')->find($item['id']);
            if (! $pin) {
                return null;
            }

            return [
                'id' => $pin->id,
                'pin_id' => $pin->pin_id,
                'board_id' => $pin->board_id,
                'link' => $pin->pin_link,
                'image' => $pin->pin_img,
                'embed' => $pin->embed_code,
                'like_count' => $pin->stats->like_count ?? 0,
            ];
        })->filter();
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
                'pins' => $this->pins,
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
