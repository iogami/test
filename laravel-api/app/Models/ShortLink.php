<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;

class ShortLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'original_url',
        'code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the visits for the short link.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ShortLinkVisit::class);
    }

    /**
     * Find a short link by its code.
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    /**
     * Create a new short link from a URL and code.
     */
    public static function createFromUrl(string $originalUrl, string $uniqueCode): self
    {
        return self::create([
            'original_url' => $originalUrl,
            'code'         => $uniqueCode
        ]);
    }

    /**
     * Find a short link with visit statistics by its code.
     */
    public static function findWithStatsByCode(string $code): ?self
    {
        return self::withCount('visits')
            ->where('code', $code)
            ->first();
    }
}
