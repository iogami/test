<?php

namespace App\Services;

use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class ShortLinkService
{
    /**
     * Maximum attempts to generate a unique code
     */
    private const MAX_ATTEMPTS = 10;

    /**
     * Create a new short link for the given URL.
     *
     * @param string $originalUrl The URL to create a short link for
     * @throws \RuntimeException When unable to generate a unique code
     * @return ShortLink
     */
    public function create(string $originalUrl): ShortLink
    {
        $code = $this->generateUniqueCode();
        return ShortLink::createFromUrl($originalUrl, $code);
    }

    /**
     * Generate a unique code for a short link.
     *
     * @throws \RuntimeException When unable to generate a unique code after maximum attempts
     * @return string
     */
    protected function generateUniqueCode(): string
    {
        $attempts = 0;
        $codeLength = Config::get('shortlink.code_length', 6);

        do {
            if ($attempts >= self::MAX_ATTEMPTS) {
                throw new \RuntimeException('Unable to generate unique code after ' . self::MAX_ATTEMPTS . ' attempts');
            }

            $code = Str::random($codeLength);
            $attempts++;
        } while (ShortLink::where('code', $code)->exists());

        return $code;
    }
}
