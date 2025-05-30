<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortLinkVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'short_link_id',
        'ip_address',
        'visited_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime'
    ];

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class);
    }
}

