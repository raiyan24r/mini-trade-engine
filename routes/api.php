<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BroadcastingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/balance-history', [OrderController::class, 'balanceHistory']);

    // Pusher broadcasting auth (supports both GET and POST)
    Route::match(['get', 'post'], '/broadcasting/auth', [BroadcastingController::class, 'auth']);
});
