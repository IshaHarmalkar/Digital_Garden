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
        'src',
    ];

    public function scopeForBoard($query, $boardId)
    {
        return $query->where('board_id', $boardId);
    }

    public function stats()
    {
        return $this->morphOne(Stat::class, 'statable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getPinLinkAttribute(): ?string
    {
        return $this->pin_id ? "https://www.pinterest.com/pin/{$this->pin_id}/" : null;
    }

    public function getPinImgAttribute(): ?string
    {
        return $this->src ? "https://i.pinimg.com/{$this->src}" : null;
    }

    public function getEmbedCodeAttribute(): ?string
    {
        return $this->pin_id ? " https://assets.pinterest.com/ext/embed.html?id={$this->pin_id}" : null;
    }
}
