<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // If user exists, log them in
                Auth::login($user);
                return redirect()->intended('/dashboard');
            } else {
                // Check if a user with this email already exists
                $existingUser = User::where('email', $googleUser->email)->first();
                
                if ($existingUser) {
                    // Update existing user with google_id
                    $existingUser->google_id = $googleUser->id;
                    $existingUser->save();
                    
                    Auth::login($existingUser);
                    return redirect()->intended('/dashboard');
                }
                
                // Create a new user
                // Generating a random string for username if not provided
                $username = strtolower(str_replace(' ', '', $googleUser->name)) . rand(100, 999);
                
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'username' => $username,
                    'role' => 'user',
                    // Password is nullable, or we can use a random strong password
                    'password' => bcrypt(Str::random(16)), 
                ]);
                
                Auth::login($newUser);
                return redirect()->intended('/dashboard');
            }
            
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login menggunakan Google. Silakan coba lagi.']);
        }
    }
}
