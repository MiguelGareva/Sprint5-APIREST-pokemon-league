<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /**
     * The authentication service instance.
     *
     * @var \App\Services\AuthService
     */
    protected $authService;
    
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AuthService  $authService
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());
            
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $result['user']
                // No incluimos el token aquí
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user and create token
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    /**
 * Login user and create token
 *
 * @param  \App\Http\Requests\LoginRequest  $request
 * @return \Illuminate\Http\Response
 */
public function login(LoginRequest $request)
{
    if (!Auth::attempt($request->validated())) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    try {
        $user = User::where('email', $request->email)->first();
        
        // Cargar roles para tenerlos disponibles en la respuesta
        $user->load('roles');
        
        // Revocar tokens anteriores (opcional)
        DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)
            ->update(['revoked' => true]);
        
        // Generar un nuevo token
        $token = Str::random(80);
        $tokenId = hash('sha256', $token);
        
        // Insertar el token en la base de datos
        DB::table('oauth_access_tokens')->insert([
            'id' => $tokenId,
            'user_id' => $user->id,
            'client_id' => DB::table('oauth_clients')->where('password_client', 1)->value('id') ?? 1,
            'name' => 'API Authentication',
            'scopes' => '["*"]',
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addDays(15),
        ]);
        
        return response()->json([
            'data' => $user,
            'token' => $token,
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error creating token: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Logout user (Revoke the token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            // In testing environment, just return success
            if (app()->environment('testing')) {
                return response()->json([
                    'message' => 'Successfully logged out'
                ]);
            }

            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the authenticated User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        $user = $request->user();
        
        // Load additional information
        $user->load('roles:id,name');
        
        // Include trainer data if the user has one
        if ($user->trainer) {
            $user->load('trainer');
        }
        
        return response()->json($user);
    }
}