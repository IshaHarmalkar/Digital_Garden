<?php

namespace Database\Seeders;

use App\Models\Native;
use App\Models\NativeQueue;
use App\Models\NotionContent;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // initalize Native Queue

        $natives = Native::all();

        foreach ($natives as $native) {
            NativeQueue::firstOrCreate(
                ['native_id' => $native->id],
                ['queue_type' => 'main']
            );
        }

        // notion

        $notions = NotionContent::all();

    }
}
