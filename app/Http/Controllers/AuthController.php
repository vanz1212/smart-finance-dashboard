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
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function showSignup()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('signup');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, true)) {
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

        Auth::login($user, true);
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

    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:60', 'alpha_dash', 'unique:users,username,' . Auth::id()],
        ], [
            'username.unique' => 'Username ini sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
        ]);

        $user = User::find(Auth::id());
        $user->username = $request->username;
        $user->save();

        return back()->with('success', 'Username berhasil diperbarui.');
    }

    public function updateEmail(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->email_verified_at) {
            return back()->with('info', 'Email Anda sudah terverifikasi dan tidak dapat diubah lagi.');
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user->email = $request->email;
        // Jika OTP sedang aktif, batalkan agar user harus minta lagi untuk email barunya
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return back()->with('success_email', 'Email berhasil diperbarui. Silakan minta kode verifikasi baru.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find(Auth::id());
        $user->avatar = $request->avatar;
        $user->save();

        return back()->with('success_avatar', 'Avatar berhasil diperbarui!');
    }
}
