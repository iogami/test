<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use App\Services\ShortLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ShortLinkController extends Controller
{
    public function __construct(protected ShortLinkService $shortLinkService)
    {
    }

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

        $shortLink = $this->shortLinkService->create($request->input('url'));

        return response()->json([
            'message' => 'Short link created successfully.',
            'data'    => [
                'original_url' => $shortLink->original_url,
                'short_url'    => url($shortLink->code),
                'code'         => $shortLink->code
            ],
        ], 200);
    }
}
