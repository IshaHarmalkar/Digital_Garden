<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    use HasFactory;

    protected $fillable = ['primary', 'secondary', 'tertiary'];

    public function entries()
    {
        return $this->hasMany(MoodEntry::class);
    }
}
