<?php

namespace Database\Seeders;

use App\Models\Mood;
use App\Models\MoodEntry;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MoodEntrySeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::create(2025, 8, 1);
        $end = Carbon::create(2025, 11, 25);

        $slots = ['morning', 'afternoon', 'night'];

        // Get all moods from DB (just grabbing IDs)
        $moodIds = Mood::pluck('id')->toArray();

        if (empty($moodIds)) {
            $this->command->warn(' No moods found. Please seed moods first.');

            return;
        }

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            foreach ($slots as $slot) {
                MoodEntry::firstOrCreate(
                    [
                        'slot' => $slot,
                        'entry_date' => $date->toDateString(),
                    ],
                    [
                        'mood_id' => $moodIds[array_rand($moodIds)],
                    ]
                );
            }
        }

        $this->command->info('Mood entries seeded from August 1, 2025 to October 31, 2025');
    }
 
    /* public function entriesForPeriod(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $entries = MoodEntry::with('mood:id,primary,secondary,tertiary')
            ->forDateRange($validated['start'], $validated['end'])
            ->orderBy('entry_date')
            ->orderBy('slot')
            ->get();

         return response()->json($entries);
    } */
}
