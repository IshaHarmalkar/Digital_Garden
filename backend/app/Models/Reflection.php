<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reflection extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'journal', 'gratitude'];

    protected $casts = [

        'day' => 'date',
    ];

    public function track()
    {
        return $this->morphOne(Track::class, 'trackable');
    }

    // Scopes
    public function scopeForDay($query, $date)
    {
        return $query->where('day', $date);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('day', [$startDate, $endDate]);
    }

    // static methods

    public static function recordDaily($journal, $gratitude, $date = null)
    {
        $date = $date ?? Carbon::today();

        // Create or update the reflection for the given day
        $reflection = self::updateOrCreate(
            ['day' => $date],
            ['journal' => $journal, 'gratitude' => $gratitude]
        );

        // Ensure it’s linked in the tracks table
        $reflection->track()->updateOrCreate(
            ['day' => $date],
            [] // only update 'day', relation fills IDs
        );

        return $reflection;
    }
}
