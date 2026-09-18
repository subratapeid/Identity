<?php

namespace Identity\Services\Auth;

use Identity\Mail\Auth\LoginOtpMail;
use Identity\Models\User;
use Identity\Models\UserLoginOtp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginOtpService
{
    /**
     * OTP expiry in minutes.
     */
    protected int $expiryMinutes = 5;

    /**
     * Generate & Send OTP.
     */
    public function generate(User $user, Request $request): UserLoginOtp
    {
        // Remove previous unused OTPs
        $user->loginOtps()
            ->whereNull('verified_at')
            ->delete();

        // Generate OTP
        $otp = random_int(100000, 999999);

        // Save OTP
        $loginOtp = UserLoginOtp::create([
            'uuid' => Str::uuid(),
            'user_id' => $user->id,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes($this->expiryMinutes),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send OTP
        $this->send($user, $otp);

        return $loginOtp;
    }

    /**
     * Verify OTP.
     */
    public function verify(UserLoginOtp $loginOtp, string $otp): bool
    {
        if ($loginOtp->verified_at) {
            return false;
        }

        if ($loginOtp->expires_at->isPast()) {
            return false;
        }

        if ($loginOtp->attempts >= 5) {
            return false;
        }

        // $loginOtp->increment('attempts');

        if (!Hash::check($otp, $loginOtp->otp_hash)) {
            return false;
        }

        $loginOtp->update([
            'verified_at' => now(),
        ]);

        return true;
    }

    /**
     * Find OTP by UUID.
     */
    public function find(string $uuid): ?UserLoginOtp
    {
        return UserLoginOtp::with('user')
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * Resend OTP.
     */
    public function resend(UserLoginOtp $loginOtp, Request $request): UserLoginOtp
    {
        $loginOtp->delete();

        return $this->generate(
            $loginOtp->user,
            $request
        );
    }

    /**
     * Send OTP.
     */


    protected function send(User $user, int $otp): void
    {
        Mail::to($user->email)
            ->send(
                new LoginOtpMail(
                    user: $user,
                    otp: $otp,
                    expiryMinutes: $this->expiryMinutes,
                )
            );
    }
}