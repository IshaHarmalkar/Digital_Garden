<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal' => 'required|string',
            'gratitude' => 'nullable|string',
            'day' => 'nullable|date',
        ]);

        $day = $validated['day'] ?? now()->toDateString();

        // recordDaily handles updateOrCreate and tracks
        $reflection = Reflection::recordDaily(
            $validated['journal'],
            $validated['gratitude'] ?? '',
            $day
        );

        return response()->json($reflection, 201);
    }

    public function today()
    {
        $reflection = Reflection::forDay(now()->toDateString())->first();

        return response()->json($reflection ?? [
            'day' => now()->toDateString(),
            'journal' => '',
            'gratitude' => '',
        ]);
    }

    /**
     * Get reflections summary for week/month/quarter
     */
    public function summary(Request $request)
    {
        $range = $request->get('range', 'week'); // week, month, quarter
        $end = now()->toDateString();

        $start = match ($range) {
            'month' => now()->startOfMonth()->toDateString(),
            'quarter' => now()->firstOfQuarter()->toDateString(),
            default => now()->startOfWeek()->toDateString(),
        };

        $reflections = Reflection::forDateRange($start, $end)->get();

        // Map each reflection directly to day => reflection
        $summary = $reflections->mapWithKeys(fn ($reflection) => [
            $reflection->day->toDateString() => [
                'journal' => $reflection->journal,
                'gratitude' => $reflection->gratitude,
            ],
        ]);

        return response()->json($summary);
    }

    /**
     * Get reflection for a specific date
     */
    public function show($date)
    {
        $reflection = Reflection::forDay($date)->first();

        return response()->json($reflection ?? [
            'day' => $date,
            'journal' => '',
            'gratitude' => '',
        ]);
    }

    // get all gratitude

    public function allGratitudes()
    {
        $gratitudes = Reflection::orderBy('day', 'desc')
            ->pluck('gratitude', 'day');

        return response()->json($gratitudes);
    }

    // get all journal
    public function allJournals()
    {
        $journals = Reflection::orderBy('day', 'desc')
            ->pluck('journal', 'day');

        return response()->json($journals);
    }
}
