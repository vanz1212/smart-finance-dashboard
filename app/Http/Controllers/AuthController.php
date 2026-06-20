<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('login');
    }

    public function showSignup()
    {
        return view('signup');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'login',
            'page_label' => 'Login',
            'route_name' => 'login.process',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:60', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
                'confirmed',
            ],
        ], [
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'password.regex' => 'Password wajib memiliki huruf kapital, angka, dan simbol.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role' => 'user',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'signup',
            'page_label' => 'Signup',
            'route_name' => 'signup.process',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'logout',
            'page_label' => 'Logout',
            'route_name' => 'logout',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function profile(Request $request)
    {
        return view('profile', [
            'isLoggedIn' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }
}
