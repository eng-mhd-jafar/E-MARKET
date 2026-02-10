<?php

use App\Http\Controllers\Api\JwtAuthController;
use App\Http\Controllers\Api\SanctumController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
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
        Route::get('AllUsers', [SanctumController::class, 'index']);
    });
});


// Sanctum Authentication Routes
Route::controller(SanctumController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/verify-OTP', 'verifyOTP')->middleware('throttle:otp-limiter');
    Route::post('/resend-OTP', 'reSendOTP')->middleware('throttle:otp-limiter');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

// payment routes
Route::post('/stripe/checkout', [PaymentController::class, 'checkout']);
Route::post('/stripe/handleWebhook', [PaymentController::class, 'handleWebhook']);

// order routes
Route::get('/products', [OrderController::class, 'index'])->middleware('throttle:Products');
Route::post('/placeOrder', [OrderController::class, 'store'])->middleware('auth:sanctum,jwt');

