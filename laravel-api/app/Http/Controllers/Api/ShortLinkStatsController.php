<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ShortLinkStatsController extends Controller
{
    public function show(string $code): JsonResponse
    {
        $link = ShortLink::findWithStatsByCode($code);

        if (!$link) {
            return response()->json([
                'message' => 'Short link not found.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'code'         => $link->code,
                'original_url' => $link->original_url,
                'created_at'   => $link->created_at,
                'visit_count'  => $link->visits_count
            ]
        ]);
    }
}
