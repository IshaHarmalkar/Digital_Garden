<?php

namespace Database\Seeders;

use App\Services\PinterestService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PinterestContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boardsJson = env('PINTEREST_BOARDS', '[]');
        $boards = json_decode($boardsJson, true) ?: [];

        $service = new PinterestService;

        foreach ($boards as $boardId) {
            Log::info("seeding board: {$boardId}");

            $result = $service->fetchAndStorePinsForBoard($boardId);

            if (isset($result['error'])) {
                Log::error("Error seeding board {$boardId} ".$result['error']);
            } else {
                Log::info("Board {$boardId} synced", $result);
            }
        }
    }
}
