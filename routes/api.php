<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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
        // Here would go routes exclusive to administrators
        // For example:
        // Route::resource('/trainers', TrainerController::class);
    });
    
    // Trainer specific routes
    Route::middleware('role:trainer')->group(function () {
        // Here would go routes exclusive to trainers
    });
});

// Public routes (no authentication required)
// Route::get('/trainers/ranking', [TrainerController::class, 'ranking']);