<?php

namespace Database\Seeders;

use App\Models\PinterestContent;
use App\Models\PinterestQueue;
use Illuminate\Database\Seeder;

class PinterestQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pins = PinterestContent::all();

        foreach ($pins as $pin) {
            PinterestQueue::firstOrCreate(
                ['pinterest_content_id' => $pin->id], // uniqueness check
                ['queue_type' => 'main']              // set if creating new
            );

        }
    }
}
