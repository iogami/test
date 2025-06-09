<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortLinkVisit extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'short_link_id',
        'ip_address',
        'visited_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visited_at' => 'datetime',
        'short_link_id' => 'integer'
    ];

    /**
     * Get the short link that owns the visit.
     */
    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class);
    }

    /**
     * Record a new visit for a short link.
     */
    public static function recordVisit(int $linkID, string $ipAddress): self
    {
        return static::create([
            'short_link_id' => $linkID,
            'ip_address'    => $ipAddress,
            'visited_at'    => now()
        ]);
    }
}

