<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use App\Services\ShortLinkService;
use Illuminate\Http\JsonResponse;

class ShortLinkController extends Controller
{
    public function __construct(
        protected ShortLinkService $shortLinkService
    ) {
    }

    public function store(StoreShortLinkRequest $request): JsonResponse
    {
        $shortLink = $this->shortLinkService->create($request->input('url'));

        return response()->json([
            'message' => 'Short link created successfully.',
            'data'    => [
                'original_url' => $shortLink->original_url,
                'short_url'    => url($shortLink->code),
                'code'         => $shortLink->code
            ],
        ], 201);
    }
}
