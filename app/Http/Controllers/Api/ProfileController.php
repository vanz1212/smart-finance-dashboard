<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->user()->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ]);

        $user = $request->user();

        if ($request->has('username')) {
            $user->username = $request->username;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                // $user->avatar might be a full URL or a path, assume it's just the filename or path in storage/app/public/avatars
                // For simplicity, let's just store the path and use asset() or Storage::url() in the model, but usually we just store the path.
                $oldPath = str_replace(url('/storage'), '', $user->avatar);
                $oldPath = ltrim($oldPath, '/');
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = url('/storage/' . $path);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function requestEmailChangeOtp(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
        ]);

        $user = $request->user();
        $newEmail = $request->new_email;

        // Generate 4-digit OTP
        $otp = (string) rand(1000, 9999);

        // Save OTP to user
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        // Save new email to cache
        Cache::put('email_change_' . $user->id, $newEmail, now()->addMinutes(15));

        try {
            // Send OTP to new email
            \Illuminate\Support\Facades\Mail::to($newEmail)->send(new \App\Mail\SendOtpMail($otp, 'verify'));
            return response()->json(['message' => 'OTP sent to new email']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send email. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmailChangeOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:4',
        ]);

        $user = $request->user();

        if ($user->otp_code !== $request->otp) {
            throw ValidationException::withMessages([
                'otp' => ['Kode OTP salah.'],
            ]);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp' => ['Kode OTP sudah kedaluwarsa.'],
            ]);
        }

        $newEmail = Cache::get('email_change_' . $user->id);

        if (!$newEmail) {
            throw ValidationException::withMessages([
                'otp' => ['Sesi perubahan email telah kedaluwarsa atau tidak valid.'],
            ]);
        }

        // Update email
        $user->update([
            'email' => $newEmail,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Cache::forget('email_change_' . $user->id);

        return response()->json([
            'message' => 'Email updated successfully',
            'user' => $user
        ]);
    }
}
