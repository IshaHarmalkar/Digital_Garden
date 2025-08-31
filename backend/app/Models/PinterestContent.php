<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinterestContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin_id',
        'board_id',
    ];

    public function scopeForBoard($query, $boardId)
    {
        return $query->where('board_id', $boardId);
    }

    public function stat()
    {
        return $this->morphOne(Stat::class, 'statable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
