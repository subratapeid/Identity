<?php

namespace Identity\Services\Auth;

use Core\Services\DataEncryptionService;
use Identity\Mail\Auth\LoginOtpMail;
use Identity\Models\User;
use Identity\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

class LoginVerificationService
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    */

    protected int $expiryMinutes = 5;

    protected int $maxAttempts = 5;

    protected string $type = 'login';

    protected string $channel = 'email';

    protected string $provider = 'internal';

    public function __construct(
        protected DataEncryptionService $encryption
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Generate & Send OTP
    |--------------------------------------------------------------------------
    */

    public function generate(
        User $user,
        Request $request
    ): UserVerification {

        /*
        |--------------------------------------------------------------------------
        | Remove Previous Pending Login Verifications
        |--------------------------------------------------------------------------
        */

        UserVerification::query()
            ->where('user_id', $user->id)
            ->where('type', $this->type)
            ->where('channel', $this->channel)
            ->whereIn('status', [
                'pending',
            ])
            ->update([
                'status' => 'cancelled',
                'updated_at' => now(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Generate OTP
        |--------------------------------------------------------------------------
        */

        $otp = (string) random_int(100000, 999999);

        /*
        |--------------------------------------------------------------------------
        | Target
        |--------------------------------------------------------------------------
        |
        | Email is encrypted in users table.
        | Decrypt only when it is required for sending.
        |
        */

        $email = $this->getUserEmail($user);

        if (!$email) {
            throw new RuntimeException(
                'User does not have a valid email address.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Verification
        |--------------------------------------------------------------------------
        */

        $verification = UserVerification::create([
            'uuid' => (string) Str::uuid(),

            'user_id' => $user->id,

            'type' => $this->type,

            'channel' => $this->channel,

            /*
             * HMAC of the normalized target.
             */
            'target_hash' => $this->hashTarget($email),

            /*
             * HMAC of the OTP.
             */
            'otp_hash' => $this->hashOtp($otp),

            'attempts' => 0,

            'max_attempts' => $this->maxAttempts,

            'status' => 'pending',

            'sent_at' => now(),

            'expires_at' => now()->addMinutes(
                $this->expiryMinutes
            ),

            'request_ip' => $request->ip(),

            'user_agent' => $request->userAgent(),

            'provider' => $this->provider,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send OTP
        |--------------------------------------------------------------------------
        */

        $this->send(
            $user,
            $email,
            $otp
        );

        return $verification;
    }

    /*
    |--------------------------------------------------------------------------
    | Verify OTP
    |--------------------------------------------------------------------------
    */

    public function verify(
        UserVerification $verification,
        string $otp
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Verification State
        |--------------------------------------------------------------------------
        */

        if ($verification->status !== 'pending') {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Expiry
        |--------------------------------------------------------------------------
        */

        if (
            $verification->expires_at === null
            || $verification->expires_at->isPast()
        ) {

            $verification->update([
                'status' => 'expired',
            ]);

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Attempts
        |--------------------------------------------------------------------------
        */

        if (
            $verification->attempts
            >= $verification->max_attempts
        ) {

            $verification->update([
                'status' => 'blocked',
            ]);

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Increment Attempt
        |--------------------------------------------------------------------------
        */

        $verification->increment('attempts');

        /*
        |--------------------------------------------------------------------------
        | Verify OTP
        |--------------------------------------------------------------------------
        */

        $otpHash = $this->hashOtp($otp);

        if (
            !hash_equals(
                $verification->otp_hash ?? '',
                $otpHash
            )
        ) {

            /*
             * Mark as failed when maximum attempts
             * have now been reached.
             */
            $verification->refresh();

            if (
                $verification->attempts
                >= $verification->max_attempts
            ) {
                $verification->update([
                    'status' => 'blocked',
                ]);
            }

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Verification Successful
        |--------------------------------------------------------------------------
        */

        $verification->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Find Verification
    |--------------------------------------------------------------------------
    */

    public function find(
        string $uuid
    ): ?UserVerification {

        return UserVerification::query()
            ->with('user')
            ->where('uuid', $uuid)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Resend OTP
    |--------------------------------------------------------------------------
    */

    public function resend(
        UserVerification $verification,
        Request $request
    ): UserVerification {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            $verification->status === 'verified'
        ) {
            return $verification;
        }

        /*
        |--------------------------------------------------------------------------
        | Cancel Existing Verification
        |--------------------------------------------------------------------------
        */

        $verification->update([
            'status' => 'cancelled',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate New Verification
        |--------------------------------------------------------------------------
        */

        return $this->generate(
            $verification->user,
            $request
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Send OTP
    |--------------------------------------------------------------------------
    */

    protected function send(
        User $user,
        string $email,
        string $otp
    ): void {

        Mail::to($email)
            ->send(
                new LoginOtpMail(
                    user: $user,
                    otp: $otp,
                    expiryMinutes: $this->expiryMinutes,
                )
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Get User Email
    |--------------------------------------------------------------------------
    */

    protected function getUserEmail(
        User $user
    ): ?string {

        if (
            empty($user->email_encrypted)
        ) {
            return null;
        }

        return $this->encryption->decrypt(
            $user->email_encrypted
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hash OTP
    |--------------------------------------------------------------------------
    |
    | user_verifications.otp_hash is CHAR(64).
    |
    | Therefore use HMAC SHA-256 instead of Hash::make(),
    | which produces a variable-length bcrypt/argon hash.
    |
    */

    protected function hashOtp(
        string $otp
    ): string {

        return hash_hmac(
            'sha256',
            trim($otp),
            $this->getHashKey()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hash Target
    |--------------------------------------------------------------------------
    |
    | Used for email/phone lookup without storing
    | the actual target in plaintext.
    |
    */

    protected function hashTarget(
        string $target
    ): string {

        return hash_hmac(
            'sha256',
            strtolower(trim($target)),
            $this->getHashKey()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hash Key
    |--------------------------------------------------------------------------
    */

    protected function getHashKey(): string
    {
        $key = config('app.key');

        if (!$key) {
            throw new RuntimeException(
                'Application encryption key is not configured.'
            );
        }

        return $key;
    }
}