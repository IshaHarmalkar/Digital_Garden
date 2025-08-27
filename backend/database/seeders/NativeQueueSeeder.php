<?php

namespace Database\Seeders;

use App\Models\Native;
use App\Models\NativeQueue;
use Illuminate\Database\Seeder;

class NativeQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $native = Native::all();

        foreach ($native as $native) {
            NativeQueue::firstOrCreate(
                ['native_id' => $native->id],
                ['queue_type' => 'main']
            );

        }
    }
}
