<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\PokemonController;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes (no authentication required)
Route::get('/trainers/ranking', [TrainerController::class, 'ranking']);

// Protected routes that require authentication
Route::middleware('auth:api')->group(function () {
    // Routes accessible to any authenticated user
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Admin specific routes
    Route::middleware('role:admin')->group(function () {
        // Admin trainer routes
        Route::get('/trainers', [TrainerController::class, 'index']);
        Route::post('/trainers', [TrainerController::class, 'store']);
        Route::put('/trainers/{trainer}', [TrainerController::class, 'update']);
        Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy']);
        // Admin pokemon routes
        Route::post('/pokemons', [PokemonController::class, 'store']);
        Route::put('/pokemons/{pokemon}', [PokemonController::class, 'update']);
        Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy']);
    });
    // Trainer specific routes
    Route::middleware('role:trainer')->group(function () {
        Route::get('/trainers/{trainer}', [TrainerController::class, 'show']);
     // Trainer Pokemon routes (any authenticated user can view)
    Route::get('/pokemons', [PokemonController::class, 'index']);
    Route::get('/pokemons/available', [PokemonController::class, 'available']);
    Route::get('/pokemons/{pokemon}', [PokemonController::class, 'show']);
    });
    // Update routes (accessible to trainers and admins)
    Route::middleware('role:admin|trainer')->group(function () {
        Route::post('/pokemons/{pokemon}/trainers/{trainer}', [PokemonController::class, 'assignToTrainer']);
        Route::delete('/pokemons/{pokemon}/trainers/{trainer}', [PokemonController::class, 'releaseFromTrainer']);
    });
});