<?php

namespace Database\Seeders;

use App\Models\NotionContent;
use App\Models\NotionQueue;
use Illuminate\Database\Seeder;

class NotionQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notions = NotionContent::all();

        foreach ($notions as $notion) {
            NotionQueue::firstOrCreate(
                ['notion_content_id' => $notion->id], // uniqueness check
                ['queue_type' => 'main']              // set if creating new
            );

        }

    }
}
