<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-passport', function () {
    $user = \App\Models\User::first();
    
    if (!$user) {
        return ['error' => 'No users found'];
    }
    
    try {
        $token = $user->createToken('Test')->accessToken;
        return ['success' => true, 'token' => $token];
    } catch (\Exception $e) {
        return [
            'success' => false, 
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});
