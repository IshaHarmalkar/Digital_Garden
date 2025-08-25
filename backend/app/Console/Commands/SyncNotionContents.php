<?php

namespace App\Console\Commands;

use App\Models\NotionContent;
use App\Services\NotionService;
use Illuminate\Console\Command;

class SyncNotionContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notion:sync {databaseId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Notion pages into the local database';

    public function __construct(private NotionService $notionService)
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $databaseId = $this->argument('databaseId');
        $pages = $this->notionService->getPagesFromDatabase($databaseId);

        if (isset($pages['error'])) {
            $this->error($pages['error']);

            return 1;
        }

        $this->info('Found '.count($pages).' pages in Notion.');

        // start progress bar
        $this->output->progressStart(count($pages));

        foreach ($pages as $page) {
            $record = NotionContent::where('notion_page_id', $page['id'])->first();

            if (! $record) {
                NotionContent::create([
                    'notion_page_id' => $page['id'],
                    'title' => $page['title'],
                    'last_edited_time' => $page['last_edited_time'],
                ]);

                $this->info("Inserted: {$page['title']}");
            } elseif ($record->last_edited_time !== $page['last_edited_time']) {

                // update page
                $record->update([
                    'title' => $page['title'],
                    'last_edited_time' => $page['last_edited_time'],
                ]);

                $this->info("Updated: {$page['title']}");
            }

            $this->output->progressAdvance();
        }

        // finish progress bar
        $this->output->progressFinish();

        $this->info('Sync Complete!');

        return 0;
    }
}
