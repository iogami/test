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
        $validator = Validator::make(['code' => $code], [
            'code' => ['required', 'regex:/^[A-Za-z0-9]{6}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $link = ShortLink::where('code', $code)->withCount('visits')->first();

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
