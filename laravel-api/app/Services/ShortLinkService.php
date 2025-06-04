<?php

namespace App\Services;

use App\Models\ShortLink;
use Illuminate\Support\Str;

class ShortLinkService
{
    public function create(string $originalUrl): ShortLink
    {
        $code = $this->generateUniqueCode();

        return ShortLink::createFromUrl($originalUrl, $code);
    }

    protected function generateUniqueCode(): string
    {
        do {
            $code = Str::random(6);
        } while (ShortLink::where('code', $code)->exists());

        return $code;
    }
}
