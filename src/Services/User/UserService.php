<?php

declare(strict_types=1);

namespace Pagelyne\Identity\Services\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Pagelyne\Identity\Models\User;
use Pagelyne\Identity\Services\DataEncryptionService;

class UserService
{
    public function __construct(
        protected DataEncryptionService $encryption,
    ) {
    }

    /**
     * Create a new Identity user.
     */
    public function create(array $data): User
    {
        /*
        |--------------------------------------------------------------------------
        | Generate username internally
        |--------------------------------------------------------------------------
        |
        | Username is an Identity concern.
        | The calling application does not need to provide it.
        |
        */
        $username = $this->generateUsername(
            $data['username_prefix'] ?? 'USR'
        );

        /*
        |--------------------------------------------------------------------------
        | Normalize email
        |--------------------------------------------------------------------------
        */

        $email = null;

        if (!empty($data['email'])) {
            $email = mb_strtolower(
                trim($data['email'])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize phone
        |--------------------------------------------------------------------------
        */

        $phone = null;

        if (!empty($data['phone'])) {
            $phone = preg_replace(
                '/\D+/',
                '',
                $data['phone']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Identity user
        |--------------------------------------------------------------------------
        */

        $userId = DB::table('users')->insertGetId([
            /*
             * Identity UUID.
             */
            'uuid' => (string) Str::uuid(),

            /*
             * Generated internally.
             */
            'username' => $username,

            /*
             * Email
             */
            'email_encrypted' => $email !== null
                ? $this->encryption->encrypt($email)
                : null,

            'email_hash' => $email !== null
                ? $this->hashValue($email)
                : null,

            'email_verified_at' => $data['email_verified_at'] ?? null,

            /*
             * Phone
             */
            'phone_encrypted' => $phone !== null
                ? $this->encryption->encrypt($phone)
                : null,

            'phone_hash' => $phone !== null
                ? $this->hashValue($phone)
                : null,

            'phone_verified_at' => $data['phone_verified_at'] ?? null,

            /*
             * Password.
             *
             * OTP-based accounts can leave this NULL.
             */
            'password' => !empty($data['password'])
                ? Hash::make($data['password'])
                : null,

            /*
             * Account status.
             */
            'status' => $data['status'] ?? 'active',

            /*
             * Login activity.
             */
            'last_login_at' => null,

            /*
             * Timestamps.
             */
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($userId);
    }

    /**
     * Generate a unique username.
     */
    protected function generateUsername(string $prefix = 'USR'): string
    {
        do {
            $username = strtoupper($prefix)
                . '-'
                . strtoupper(Str::random(8));

        } while (
            DB::table('users')
                ->where('username', $username)
                ->exists()
        );

        return $username;
    }

    /**
     * Generate the blind index used for exact lookup.
     */
    protected function hashValue(string $value): string
    {
        return hash_hmac(
            'sha256',
            mb_strtolower(trim($value)),
            config('app.key')
        );
    }
}