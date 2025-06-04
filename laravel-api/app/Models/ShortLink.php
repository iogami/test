<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $fillable = [
        'original_url',
        'code',
    ];

    public function visits()
    {
        return $this->hasMany(ShortLinkVisit::class);
    }

    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    public static function createFromUrl(string $originalUrl, string $uniqueCode): self
    {
        return self::create([
            'original_url' => $originalUrl,
            'code'         => $uniqueCode
        ]);
    }

    public static function findWithStatsByCode(string $code): ?self
    {
        return self::withCount('visits')
            ->where('code', $code)
            ->first();
    }
}
