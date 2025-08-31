<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinterestQueue extends Model
{
    use HasFactory;

    protected $fillable = ['pinterest_content_id', 'queue_type'];

    public function pinterestContent()
    {
        return $this->belongsTo(PinterestContent::class);
    }

    // scopes

    public function scopeMain($query)
    {
        return $query->where('queue_type', 'main');
    }

    public function scopePriority($query)
    {
        return $query->where('queue_type', 'priority');
    }

    public function scopeOldestInQueue($query)
    {
        return $query->orderBy('id', 'asc');
    }

    public function scopeNewestInQueue($query)
    {
        return $query->orderBy('id', 'desc');
    }
}
