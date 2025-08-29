<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Native extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'content',
        'image_path',
        'url',
        'like_count',
    ];

    protected $appends = ['image_url'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // get full url
    public function getImageUrlAttribute()
    {
        return $this->image_path
        ? asset('storage/natives/'.$this->image_path)
        : null;
    }

    public function stats()
    {
        return $this->morphOne(Stat::class, 'statable');
    }
}
