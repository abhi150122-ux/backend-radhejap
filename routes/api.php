<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JapController;
use App\Http\Controllers\Api\MantraController;
use App\Http\Controllers\Api\StateController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'radhe-jap-api',
        'timestamp' => now()->toISOString(),
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/state', [StateController::class, 'show']);
    Route::patch('/settings', [StateController::class, 'update']);
    Route::post('/mantras', [MantraController::class, 'store']);
    Route::post('/jap', [JapController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
