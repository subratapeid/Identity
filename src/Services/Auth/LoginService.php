<?php

namespace Identity\Services\Auth;

use Identity\Models\User;
use Identity\Models\UserSecurity;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    /**
     * Find user by username, email or phone.
     *
     * Email and phone are encrypted in the database.
     * Therefore, exact lookup is performed using their blind indexes.
     */
    public function findUser(string $username): ?User
    {
        $username = trim($username);

        if ($username === '') {
            return null;
        }

        $hash = $this->generateLookupHash($username);

        return User::query()
            ->where(function ($query) use ($username, $hash) {

                $query
                    ->where('username', $username)
                    ->orWhere('email_hash', $hash)
                    ->orWhere('phone_hash', $hash);
            })
            ->first();
    }

    /**
     * Authenticate user credentials.
     */
    public function authenticate(
        string $username,
        string $password
    ): array {

        $user = $this->findUser($username);

        /*
        |--------------------------------------------------------------------------
        | User Not Found
        |--------------------------------------------------------------------------
        |
        | Do not reveal whether the username/email/phone exists.
        |
        */

        if (!$user) {
            return $this->invalidCredentialsResponse();
        }

        /*
        |--------------------------------------------------------------------------
        | Account Status
        |--------------------------------------------------------------------------
        */

        if (!$this->isAccountActive($user)) {
            return [
                'success' => false,
                'message' => $this->getStatusMessage($user),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Security Record
        |--------------------------------------------------------------------------
        */

        $security = $this->getSecurity($user);

        /*
        |--------------------------------------------------------------------------
        | Account Lock
        |--------------------------------------------------------------------------
        */

        if ($this->isLocked($security)) {
            return [
                'success' => false,
                'message' => 'Your account is temporarily locked. Please try again later.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($password, $user->password)) {

            $this->incrementFailedAttempts($security);

            return $this->invalidCredentialsResponse();
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Login Security
        |--------------------------------------------------------------------------
        */

        $this->resetFailedAttempts($security);

        /*
        |--------------------------------------------------------------------------
        | Email Verification
        |--------------------------------------------------------------------------
        */

        if (
            $this->isEmailVerificationRequired($security)
            && !$this->isEmailVerified($user)
        ) {
            return [
                'success' => false,
                'message' => 'Please verify your email address before logging in.',
                'code' => 'EMAIL_NOT_VERIFIED',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Phone Verification
        |--------------------------------------------------------------------------
        */

        if (
            $this->isPhoneVerificationRequired($security)
            && !$this->isPhoneVerified($user)
        ) {
            return [
                'success' => false,
                'message' => 'Please verify your phone number before logging in.',
                'code' => 'PHONE_NOT_VERIFIED',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Password Change Required
        |--------------------------------------------------------------------------
        */

        if ($security->password_change_required) {
            return [
                'success' => false,
                'message' => 'You must change your password before continuing.',
                'code' => 'PASSWORD_CHANGE_REQUIRED',
                'user' => $user,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Password Expiry
        |--------------------------------------------------------------------------
        */

        if ($this->isPasswordExpired($security)) {
            return [
                'success' => false,
                'message' => 'Your password has expired. Please change your password.',
                'code' => 'PASSWORD_EXPIRED',
                'user' => $user,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Two-Factor Authentication
        |--------------------------------------------------------------------------
        */

        if ($this->isTwoFactorRequired($security)) {
            return [
                'success' => true,
                'requires_two_factor' => true,
                'user' => $user,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Authentication Successful
        |--------------------------------------------------------------------------
        */

        return [
            'success' => true,
            'requires_two_factor' => false,
            'user' => $user,
        ];
    }

    /**
     * Get user's security record.
     */
    protected function getSecurity(User $user): UserSecurity
    {
        return UserSecurity::query()
            ->firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'failed_login_attempts' => 0,
                    'two_factor_required' => false,
                    'email_verification_required' => true,
                    'phone_verification_required' => false,
                    'security_alerts_enabled' => true,
                    'revoke_sessions_on_password_change' => true,
                    'revoke_tokens_on_password_change' => true,
                ]
            );
    }

    /**
     * Check account status.
     */
    public function isAccountActive(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Get account status message.
     */
    public function getStatusMessage(User $user): string
    {
        return match ($user->status) {

            'inactive' =>
            'Your account is inactive.',

            'pending' =>
            'Your account is pending activation.',

            'blocked' =>
            'Your account has been blocked.',

            'suspended' =>
            'Your account has been suspended.',

            'locked' =>
            'Your account has been locked.',

            default =>
            'Unable to login.',
        };
    }

    /**
     * Check whether security lock is active.
     */
    public function isLocked(UserSecurity $security): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Permanent / Manual Lock
        |--------------------------------------------------------------------------
        */

        if ($security->is_locked) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Temporary Lock
        |--------------------------------------------------------------------------
        */

        if (
            $security->locked_until !== null
            && now()->lessThan($security->locked_until)
        ) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Expired Temporary Lock
        |--------------------------------------------------------------------------
        |
        | Clean the temporary lock automatically.
        |
        */

        if (
            $security->locked_until !== null
            && now()->greaterThanOrEqualTo($security->locked_until)
        ) {
            $security->locked_until = null;
            $security->save();
        }

        return false;
    }

    /**
     * Increment failed login attempts.
     */
    public function incrementFailedAttempts(
        UserSecurity $security
    ): void {

        $security->failed_login_attempts++;
        $security->last_failed_login_at = now();

        /*
        |--------------------------------------------------------------------------
        | Temporary Login Lock
        |--------------------------------------------------------------------------
        |
        | Five failed attempts = 15 minute lock.
        |
        */

        if ($security->failed_login_attempts >= 5) {

            $security->locked_until = now()->addMinutes(15);
            $security->locked_at = now();
            $security->lock_reason = 'too_many_failed_login_attempts';
        }

        $security->save();
    }

    /**
     * Reset failed login attempts after successful authentication.
     */
    public function resetFailedAttempts(
        UserSecurity $security
    ): void {

        $security->failed_login_attempts = 0;
        $security->last_failed_login_at = null;

        /*
         * Do not remove a permanent/manual lock here.
         *
         * Only remove an expired temporary lock.
         */
        if (
            !$security->is_locked
            && (
                $security->locked_until === null
                || now()->greaterThanOrEqualTo($security->locked_until)
            )
        ) {
            $security->locked_until = null;
            $security->locked_at = null;
            $security->lock_reason = null;
        }

        $security->save();
    }

    /**
     * Update last login timestamp.
     *
     * IP address is intentionally not stored in users.
     * Login IP/history should belong to login history/security
     * tracking tables.
     */
    public function updateLastLogin(User $user): void
    {
        $user->last_login_at = now();

        $user->save();
    }

    /**
     * Check if email is verified.
     */
    public function isEmailVerified(User $user): bool
    {
        return $user->email_verified_at !== null;
    }

    /**
     * Check if phone is verified.
     */
    public function isPhoneVerified(User $user): bool
    {
        return $user->phone_verified_at !== null;
    }

    /**
     * Check whether email verification is required.
     */
    public function isEmailVerificationRequired(
        UserSecurity $security
    ): bool {
        return (bool) $security->email_verification_required;
    }

    /**
     * Check whether phone verification is required.
     */
    public function isPhoneVerificationRequired(
        UserSecurity $security
    ): bool {
        return (bool) $security->phone_verification_required;
    }

    /**
     * Check whether two-factor authentication is required.
     *
     * Actual 2FA configuration belongs to user_two_factor_auth.
     */
    public function isTwoFactorRequired(
        UserSecurity $security
    ): bool {
        return (bool) $security->two_factor_required;
    }

    /**
     * Check whether password change is required.
     */
    public function isPasswordChangeRequired(
        UserSecurity $security
    ): bool {
        return (bool) $security->password_change_required;
    }

    /**
     * Check whether password has expired.
     */
    public function isPasswordExpired(
        UserSecurity $security
    ): bool {
        return $security->password_expires_at !== null
            && now()->greaterThanOrEqualTo(
                $security->password_expires_at
            );
    }

    /**
     * Generate deterministic lookup hash.
     *
     * Used for encrypted email/phone lookup.
     */
    protected function generateLookupHash(string $value): string
    {
        return hash_hmac(
            'sha256',
            strtolower(trim($value)),
            config('app.key')
        );
    }

    /**
     * Generic invalid credential response.
     *
     * This prevents user enumeration.
     */
    protected function invalidCredentialsResponse(): array
    {
        return [
            'success' => false,
            'message' => 'Invalid username, email/phone or password.',
        ];
    }

    /**
     * Load authentication-related relationships.
     */
    public function loadRelations(User $user): User
    {
        return $user->load([
            'profile',
            'security',
            'loginOtps',
            'loginAttempts',
            'loginLogs',
            'creator',
            'updater',
        ]);
    }
}