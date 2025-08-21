<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'like_count',
        'last_sent_at',
        'see_again_soon',
    ];

    public function statable()
    {
        return $this->morphTo();
    }
}
