<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password'])

        ]);
        $token = auth('api')->login($user);
        return $this->respondWithToken($token);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }


    /**
     * Find or create a user based on provider details.
     */
    private function findOrCreateUser(string $provider, $providerUser): User
    {
        return User::firstOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $providerUser->id,
            ],
            [
                'name' => $providerUser->name,
                'email' => $providerUser->email ?? "{$providerUser->id}@{$provider}.com", // Handle missing email
                'password' => Hash::make(Str::random(12)),
                'provider_token' => $providerUser->token,
            ]
        );
    }

    /**
     * Generate a response containing the authentication token.
     */
    // private function respondWithToken(string $token, User $user): \Illuminate\Http\JsonResponse
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::factory()->getTTL() * 60,
    //         'user' => $user,
    //     ]);
    // }

    /**
     * Handle authentication exceptions.
     */
    private function handleAuthException(\Throwable $e)
    {
        return response()->json([
            'error' => 'Authentication failed',
            'message' => $e->getMessage(),
        ], 500);
    }
}
