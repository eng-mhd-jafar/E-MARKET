<?php

use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/firebase-test', [FirebaseController::class, 'test']);
Route::get('/StoreDate', [FirebaseController::class, 'store']);