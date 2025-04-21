<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\PokemonController;
use App\Http\Controllers\API\BattleController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->middleware(['throttle']);

// Public routes (no authentication required)
Route::get('/trainers/ranking', [TrainerController::class, 'ranking']);
// Protected routes that require authentication
Route::middleware('auth:api')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Routes accessible to any authenticated user
    Route::get('/trainers/{trainer}', [TrainerController::class, 'show'])->where('trainer', '[0-9]+');
    Route::get('/pokemons', [PokemonController::class, 'index']);
    Route::get('/pokemon-list-available', [PokemonController::class, 'listAvailablePokemons']);
    Route::get('/pokemons/{pokemon}', [PokemonController::class, 'show'])->where('pokemon', '[0-9]+');
    Route::get('/battles', [BattleController::class, 'index']);
    Route::get('/battles/{battle}', [BattleController::class, 'show']);
    Route::post('/battles', [BattleController::class, 'store']);
    // Trainer role routes
    Route::middleware('role:trainer')->group(function () {
        // Trainer-specific routes
        //Route::get('/trainers/{trainer}', [TrainerController::class, 'show'])->where('trainer', '[0-9]+');
        
        
        // Pokemon assignment routes (trainers can modify their own pokemons)
        Route::post('/pokemons/{pokemon}/trainers/{trainer}', [PokemonController::class, 'assignToTrainer']);
        Route::delete('/pokemons/{pokemon}/trainers/{trainer}', [PokemonController::class, 'releaseFromTrainer']);
        
        // New routes for Step 6
        Route::post('/battles/simulate', [BattleController::class, 'simulateBattle']);
        Route::post('/pokemons/{pokemon}/transfer/{trainer}', [PokemonController::class, 'transferPokemon']);
    });
    
    // Admin role routes
    Route::middleware('role:admin')->group(function () {
        // Admin trainer management
        Route::get('/trainers', [TrainerController::class, 'index']);
        Route::post('/trainers', [TrainerController::class, 'store']);
        Route::put('/trainers/{trainer}', [TrainerController::class, 'update']);
        Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy']);
        
        // Admin pokemon management
        Route::post('/pokemons', [PokemonController::class, 'store']);
        Route::put('/pokemons/{pokemon}', [PokemonController::class, 'update']);
        Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy']);
        
        // Admin battle management
        Route::delete('/battles/{battle}', [BattleController::class, 'destroy']);
        
        // New admin routes for Step 6
        Route::post('/trainers/{trainer}/points', [TrainerController::class, 'updatePoints']);
    });
    
    // Additional ranking routes (Step 6)
    Route::get('/trainers/top/{count?}', [TrainerController::class, 'topTrainers']);
    Route::get('/trainers/{trainer}/similar/{range?}', [TrainerController::class, 'similarTrainers']);
    Route::get('/trainers/stats/monthly', [TrainerController::class, 'monthlyStats']);
});