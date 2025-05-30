<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{code}', [RedirectController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]{6}');
