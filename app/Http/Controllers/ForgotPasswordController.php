<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // Generate 6 digit OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otpCode, 'reset'));

        // Pass email to the reset view
        return redirect()->route('password.reset.form')->with([
            'email' => $user->email,
            'success_otp' => 'Kode OTP untuk reset password telah dikirim ke email Anda.'
        ]);
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = session('email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi kedaluwarsa, silakan minta kode kembali.']);
        }

        return view('auth.reset-password', ['email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
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

        $user = User::where('email', $request->email)->first();

        if (!$user->otp_code || !$user->otp_expires_at) {
            return back()->withErrors(['otp' => 'Tidak ada kode OTP yang aktif. Silakan minta kode baru.'])->withInput($request->only('email'));
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.'])->withInput($request->only('email'));
        }

        if ($request->otp !== $user->otp_code) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput($request->only('email'));
        }

        // OTP is valid, reset password
        $user->password = Hash::make($request->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login menggunakan password baru.');
    }
}
