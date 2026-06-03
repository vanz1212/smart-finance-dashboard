<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = env('LOGIN_EMAIL', 'admin@smartfinance.local');
        $password = env('LOGIN_PASSWORD', 'password');
        $passwordHash = env('LOGIN_PASSWORD_HASH');

        $validPassword = $passwordHash
            ? Hash::check($credentials['password'], $passwordHash)
            : $credentials['password'] === $password;

        if ($credentials['email'] !== $email || ! $validPassword) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('smart_finance_logged_in', true);

        return redirect()->route('page.selector');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('smart_finance_logged_in');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function profile(Request $request)
    {
        return view('profile', [
            'isLoggedIn' => (bool) $request->session()->get('smart_finance_logged_in', false),
            'email' => env('LOGIN_EMAIL', 'admin@smartfinance.local'),
        ]);
    }
}
