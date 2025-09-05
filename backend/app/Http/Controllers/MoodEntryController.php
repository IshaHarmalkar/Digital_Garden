<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\MoodEntry;
use Illuminate\Http\Request;

class MoodEntryController extends Controller
{
    // List moods
    public function moods()
    {
        return Mood::all();
    }

    public function moodTree()
    {
        $moods = Mood::all();

        $tree = $moods->groupBy('primary')->map(function ($group) {
            return $group->groupBy('secondary')->map(function ($subgroup) {
                return $subgroup->map(function ($mood) {
                    return [
                        'id' => $mood->id,
                        'tertiary' => $mood->tertiary,
                    ];
                });
            });
        });

        return response()->json($tree);
    }

    // log a mood request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood_id' => 'required|exists:moods,id',
            'slot' => 'required|in:morning,afternoon,night',
            'entry_date' => 'required|date',
        ]);

        $entry = MoodEntry::updateOrCreate(
            ['slot' => $validated['slot'], 'entry_date' => $validated['entry_date']],
            ['mood_id' => $validated['mood_id']]

        );

        $entry->load('mood');

        return response()->json($entry, 201);

    }

    // get today's entry

    public function today()
    {
        $today = now()->toDateString();

        return MoodEntry::with('mood')
            ->where('entry_date', $today)
            ->orderByRaw("FIELD(slot, 'morning','afternoon','night')")
            ->get();

    }

    // weekly or monthly summary -> grouped by primary feeling
    public function summary(Request $request)
    {

        $range = $request->get('range', 'week'); // week, month, quarter
        $start = match ($range) {
            'month' => now()->startOfMonth(),
            'quarter' => now()->firstOfQuarter(),
            default => now()->startOfWeek(),
        };

        $entries = MoodEntry::with('mood')
            ->where('entry_date', '>=', $start)
            ->get();

        return $entries->groupBy(fn ($e) => $e->mood->primary)
            ->map(fn ($group) => $group->count());

    }
}
