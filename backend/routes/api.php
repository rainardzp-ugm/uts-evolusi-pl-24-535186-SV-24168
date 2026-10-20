<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;

// Public Endpoint
Route::post('/login', [AuthController::class, 'login']);

// Protected Endpoints (Membutuhkan Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('books', BookController::class);
});