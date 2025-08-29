<?php

namespace App\Mail;

use App\Models\Native;
use App\Models\NativeQueue;
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
        $curated = collect();
        
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

            //pop 
            $priority->delete();


        //main q for native
        $main = NativeQueue::main()->oldestInQueue()->first();
        if($main){
            $content = $main->native;
            $nativeItems->push($content);

            $content->newsletters()->create();
            $content->stats()->updateOrCreate([], [
                'last_sent_at' => now(),
            ]);

            $main->delete();

        }

        return $nativeItems;

            // log to newsletter

    }

       
    }

    private function curateNotion()
    {
        $notionItems = collect();

        //priority 
        $priority = NotionQueue::priority()->oldestInQueue()->first();
        if($priority){
            $content = $priority->notionContent;
            $notionItems->push($content);

            $content->newsletters()->create();
            $content->stats()->updateOrCreate([], [
                'last_sent_at' => now(),
            ]);

            $priority->delete();
        }

        //oldest + new curate
        if($notionItems->isEmpty()){
            $oldest = NotionQueue::main()->oldestInQueue()->first();
            $newest = NotionQueue::main()->newestInQueue()->first();

            foreach([$oldest, $newest] as $queueItem){
                if($queueItem){

                    $content = $queueItem->notionContent;
                    $notionItems->push($content);

                    $content->newsletters()->create();
                    $content->stats()->updateOrCreate([], [
                        'last_sent_at' => now(),
                    ]);

                    $queueItem->delete();
                }
            }
        }


        //map to title + URL format for email

        return $notionItems->map(function($content){

            $page = Notion::pages()->find($content->notion_page_id);

            return[
                'titile' => $content->title,
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
