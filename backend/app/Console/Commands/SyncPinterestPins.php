<?php

namespace App\Console\Commands;

use App\Services\PinterestService;
use Illuminate\Console\Command;

class SyncPinterestPins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinterest:sync {boardId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Pinterest pins for a board into the local database';

    public function __construct(private PinterestService $pinterestService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $boardsJson = env('PINTEREST_BOARDS', '[]');
        $boards = json_decode($boardsJson, true) ?: [];

        if (empty($boards)) {
            $this->warn('No Pinterest boards configured.');

            return 0;
        }

        $this->info('Starting Pinterest sync for '.count($boards).' boards...');

        foreach ($boards as $boardId) {
            $this->info("Syncing board: {$boardId}");

            $result = $this->pinterestService->fetchAndStorePinsForBoard($boardId);

            if (isset($result['error'])) {
                $this->error("Error syncing board {$boardId}: ".$result['error']);
            } else {
                $saved = $result['pins_saved'] ?? 0;
                $this->info("Board {$boardId} synced successfully. Pins saved: {$saved}");
            }
        }

        $this->info('Pinterest sync complete for all boards.');

        return 0;
    }
}
