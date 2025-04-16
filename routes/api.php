<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TrainerController;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes that require authentication
Route::middleware('auth:api')->group(function () {
    // Routes accessible to any authenticated user
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Admin specific routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('trainers', TrainerController::class);
    });
    
    // Trainer specific routes
    Route::middleware('role:trainer')->group(function () {
        Route::get('/trainers/{trainer}', [TrainerController::class, 'show']);
    });
});

// Public routes (no authentication required)
Route::get('/trainers/ranking', [TrainerController::class, 'ranking']);