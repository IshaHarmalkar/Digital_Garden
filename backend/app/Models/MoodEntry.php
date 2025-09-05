<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = ['mood_id', 'slot', 'entry_date'];

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }
}
