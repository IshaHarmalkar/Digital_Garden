<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotionContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'notion_page_id',
        'title',

    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
