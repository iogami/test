<?php

use App\Http\Controllers\Api\ShortLinkController;
use App\Http\Controllers\Api\ShortLinkStatsController;
use App\Http\Controllers\Api\AuthController;

Route::middleware(['throttle:short_links'])->group(function () {
    Route::post('/short-links', [ShortLinkController::class, 'store']);
});

Route::post('/get-token', [AuthController::class, 'generateToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/short-links/{code}/stats', [ShortLinkStatsController::class, 'show'])
        ->where('code', '[A-Za-z0-9]{6}');
});
