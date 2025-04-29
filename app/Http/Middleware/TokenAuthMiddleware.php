<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Buscar el token en la base de datos
        $hashedToken = hash('sha256', $token);
        $accessToken = DB::table('oauth_access_tokens')
            ->where('id', $hashedToken)
            ->where('revoked', 0)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$accessToken) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Cargar el usuario con sus roles
        $user = User::with('roles')->find($accessToken->user_id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 401);
        }

        // Autenticar al usuario manualmente
        Auth::login($user);
        
        // Para debugging (opcional) - descomentar si necesitas verificar roles
        // \Log::info('User authenticated: ' . $user->name);
        // \Log::info('User roles: ' . $user->roles->pluck('name'));
        
        return $next($request);
    }
}