<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string'
        ]);

        try {
            // Retrieve Google user details from token
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->access_token);

            // Find or create user
            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            if (!$user) {
                // Generate unique username
                $username = strtolower(str_replace(' ', '', $googleUser->name)) . rand(100, 999);
                
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'username' => $username,
                    'role' => 'user',
                    'password' => bcrypt(Str::random(16)), 
                ]);
            } else {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            // Create Sanctum Token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to authenticate with Google.',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
