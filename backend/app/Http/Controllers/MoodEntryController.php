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
        // $moods = Mood::all();

        $tree = Mood::select('id', 'primary', 'secondary', 'tertiary')
            ->get()
            ->groupBy(['primary', 'secondary'])
            ->map(function ($primaryGroup) {
                return $primaryGroup->map(function ($secondaryGroup) {
                    return $secondaryGroup->map(function ($mood) {
                        return [
                            'id' => $mood->id,
                            'tertiary' => $mood->tertiary,
                        ];
                    })->values(); // Reset keys for clean JSON
                });
            });

        return response()->json($tree);
    }

    // get all mood entries
    public function index()
    {
        return MoodEntry::with('mood')->get();
    }

    // get mood entries for a period
    public function entriesByRange(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        return MoodEntry::with('mood')
            ->forDateRange($validated['start'], $validated['end'])
            ->orderBy('entry_date')
            ->orderBy('slot')
            ->get();
    }

    public function entriesPrimaryByRange(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $entries = MoodEntry::with('mood:id,primary') // only load needed mood field
            ->forDateRange($validated['start'], $validated['end'])
            ->orderBy('entry_date')
            ->orderBy('slot')
            ->get(['id', 'mood_id', 'slot', 'entry_date']);

        $grouped = $entries->groupBy(function ($entry) {
            return $entry->entry_date->toDateString();
        });

        $result = $grouped->map(function ($dayEntries, $date) {
            return collect(['morning', 'afternoon', 'night'])->mapWithKeys(function ($slot) use ($dayEntries) {
                $entry = $dayEntries->firstWhere('slot', $slot);

                return [
                    $slot => $entry ? $entry->mood?->primary : null,
                ];
            });
        });

        return response()->json($result);
    }

    // log a mood request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood_id' => 'required|exists:moods,id',
            'slot' => 'required|in:morning,afternoon,night',
            'entry_date' => 'nullable|date',
        ]);

        $entryDate = $validated['entry_date'] ?? now()->toDateString();

        // Use updateOrCreate with the unique constraint you defined
        $entry = MoodEntry::updateOrCreate(
            [
                'slot' => $validated['slot'],
                'entry_date' => $entryDate,
            ],
            ['mood_id' => $validated['mood_id']]
        );

        $entry->load('mood');

        return response()->json($entry, 201);

    }
}
