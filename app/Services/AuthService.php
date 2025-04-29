<?php

namespace App\Services;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenFactory;

class AuthService
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
{
     // Create the user
     $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    // Assign role (default to trainer if not specified)
    $role = $data['role'] ?? 'trainer';
    $user->assignRole($role);

    // Create trainer for users with trainer role
    if ($role === 'trainer') {
        Trainer::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'points' => 0,
        ]);
    }

    return [
        'user' => $user,
        //'token' => null // O simplemente no incluirlo
    ];
}
}