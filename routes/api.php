<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/check-code', 'CheckCode');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::post('/stripe/checkout', [StripeController::class, 'checkout']);

Route::get('/products', [StripeController::class, 'index']);
Route::post('/CreateOreder', [OrderController::class, 'store'])->middleware('auth:sanctum');

Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
