<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ShortLinkController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $originalUrl = $request->input('url');
        $code        = $this->generateUniqueCode();

        $shortLink = ShortLink::create([
            'original_url' => $originalUrl,
            'code'         => $code,
        ]);

        return response()->json([
            'message' => 'Short link created successfully.',
            'data'    => [
                'original_url' => $shortLink->original_url,
                'short_url'    => url($code),
                'code'         => $shortLink->code
            ],
        ], 200);
    }

    protected function generateUniqueCode(): string
    {
        do {
            $code = Str::random(6);
        } while (ShortLink::where('code', $code)->exists());

        return $code;
    }
}
