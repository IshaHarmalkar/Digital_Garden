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

    // / get today's entries with all slots represented
    public function today()
    {
        $today = now()->toDateString();

        // Use scope and get only what we need
        $entries = MoodEntry::forDate($today)
            ->with('mood:id,primary,secondary,tertiary') // Only load needed mood fields
            ->get()
            ->keyBy('slot');

        // Create response with all slots represented
        $slots = ['morning', 'afternoon', 'night'];

        $result = collect($slots)->mapWithKeys(function ($slot) use ($entries) {
            return [$slot => $entries->get($slot, [
                'slot' => $slot,
                'mood' => null,
                'entry_date' => now()->toDateString(),
            ])];
        });

        return response()->json($result);
    }

    public function summary(Request $request)
    {
        $range = $request->get('range', 'week');
        $end = now()->toDateString();

        $start = match ($range) {
            'month' => now()->startOfMonth()->toDateString(),
            'quarter' => now()->firstOfQuarter()->toDateString(),
            default => now()->startOfWeek()->toDateString(),
        };

        // Use database grouping for better performance
        $summary = MoodEntry::forDateRange($start, $end)
            ->join('moods', 'mood_entries.mood_id', '=', 'moods.id')
            ->select('moods.primary')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('moods.primary')
            ->pluck('count', 'primary');

        // Also get total entries and coverage stats
        $totalEntries = MoodEntry::forDateRange($start, $end)->count();
        $totalPossibleEntries = now()->parse($start)->diffInDays(now()->parse($end)) * 3; // 3 slots per day

        return response()->json([
            'summary' => $summary,
            'stats' => [
                'total_entries' => $totalEntries,
                'total_possible' => $totalPossibleEntries,
                'completion_rate' => $totalPossibleEntries > 0 ? round(($totalEntries / $totalPossibleEntries) * 100, 1) : 0,
            ],
            'date_range' => ['start' => $start, 'end' => $end],
        ]);
    }

    // specific date mood entries
    public function show($date)
    {
        $entries = MoodEntry::forDate($date)
            ->with('mood:id,primary,secondary,tertiary')
            ->get()
            ->keyBy('slot');

        $slots = ['morning', 'afternoon', 'night'];

        $result = collect($slots)->mapWithKeys(function ($slot) use ($entries, $date) {
            return [$slot => $entries->get($slot, [
                'slot' => $slot,
                'mood' => null,
                'entry_date' => $date,
            ])];
        });

        return response()->json($result);
    }

    // mood trends over time
    public function trends(Request $request)
    {
        $days = $request->get('days', 7); // Default 7 days
        $endDate = now()->toDateString();
        $startDate = now()->subDays($days)->toDateString();

        $trends = MoodEntry::forDateRange($startDate, $endDate)
            ->join('moods', 'mood_entries.mood_id', '=', 'moods.id')
            ->select('mood_entries.entry_date', 'mood_entries.slot', 'moods.primary')
            ->orderBy('mood_entries.entry_date')
            ->orderByRaw("FIELD(mood_entries.slot, 'morning', 'afternoon', 'night')")
            ->get()
            ->groupBy('entry_date');

        return response()->json($trends);
    }
}
