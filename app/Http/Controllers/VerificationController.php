<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use Carbon\Carbon;

class VerificationController extends Controller
{
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'Email Anda sudah terverifikasi.');
        }

        // Generate 6 digit OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otpCode, 'verification'));

        return back()->with('success_otp', 'Kode OTP telah dikirim ke email Anda. Silakan periksa kotak masuk atau spam.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'Email Anda sudah terverifikasi.');
        }

        if (!$user->otp_code || !$user->otp_expires_at) {
            return back()->withErrors(['otp' => 'Tidak ada kode OTP yang aktif. Silakan minta kode baru.']);
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        if ($request->otp !== $user->otp_code) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // OTP is valid
        $user->email_verified_at = Carbon::now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return back()->with('success', 'Email berhasil terverifikasi!');
    }
}
