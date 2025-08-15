<?php

namespace Database\Seeders;

use App\Models\Native;
use Illuminate\Database\Seeder;

class NativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Native::factory()
            ->count(20)
            ->create();

    }
}
