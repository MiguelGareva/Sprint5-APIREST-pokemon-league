<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Asegurarse de que el usuario está autenticado
        if (!$request->user()) {
            return response()->json([
                'message' => 'You do not have permission to access this resource'
            ], 403);
        }
    
        // Permitir acceso si el usuario es admin o tiene el rol específico
        if ($request->user()->hasRole('admin') || $request->user()->hasRole($role)) {
            return $next($request);
        }
    
        return response()->json([
            'message' => 'You do not have permission to access this resource'
        ], 403);
    }
}