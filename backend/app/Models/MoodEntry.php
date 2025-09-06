<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = ['mood_id', 'slot', 'entry_date'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

    public function track()
    {
        return $this->morphOne(Track::class, 'trackable');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('entry_date', $date);
    }

    public function scopeForDateRange($query, $start, $end)
    {
        return $query->whereBetween('entry_date', [$start, $end]);
    }

    protected static function booted()
    {
        static::saved(function ($entry) {
            $entry->track()->updateOrCreate(
                ['day' => $entry->entry_date],
                [] // relation auto-fills trackable_id + type
            );
        });
    }
}
