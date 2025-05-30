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
}
