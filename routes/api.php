<?php

use App\Http\Controllers\Api\JwtAuthController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// JWT Authentication Routes
Route::prefix('jwt')->group(function () {
    Route::post('register', [JwtAuthController::class, 'register']);
    Route::post('login', [JwtAuthController::class, 'login']);

    Route::middleware('auth:jwt')->group(function () {
        Route::get('me', [JwtAuthController::class, 'me']);
        Route::post('logout', [JwtAuthController::class, 'logout']);
        Route::post('refresh', [JwtAuthController::class, 'refresh']);
    });
});


// Sanctum Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/check-code', 'CheckCode');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::post('/stripe/checkout', [StripeController::class, 'checkout']);

Route::get('/products', [OrderController::class, 'index'])->middleware('throttle:Products');
Route::post('/CreateOreder', [OrderController::class, 'store'])->middleware('auth:sanctum');

Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
