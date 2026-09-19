<?php

namespace Pagelyne\Identity\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Pagelyne\Identity\Services\DataEncryptionService;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /** @var DataEncryptionService $encryption */
        $encryption = app(DataEncryptionService::class);

        $users = [
            [
                'username' => 'superadmin',
                'email' => 'superadmin@example.com',
                'phone' => '9876543210',
                'password' => 'password',

                'first_name' => 'Super',
                'middle_name' => null,
                'last_name' => 'Admin',
                'display_name' => 'Super Admin',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'bio' => 'System super administrator.',
            ],

            [
                'username' => 'admin',
                'email' => 'subratap.eid@gmail.com',
                'phone' => '9876543211',
                'password' => 'password',

                'first_name' => 'System',
                'middle_name' => null,
                'last_name' => 'Admin',
                'display_name' => 'System Admin',
                'date_of_birth' => '1992-05-15',
                'gender' => 'male',
                'bio' => 'System administrator.',
            ],

            [
                'username' => 'customer',
                'email' => 'customer@example.com',
                'phone' => '9876543212',
                'password' => 'password',

                'first_name' => 'Test',
                'middle_name' => null,
                'last_name' => 'Customer',
                'display_name' => 'Test Customer',
                'date_of_birth' => '1995-08-20',
                'gender' => 'male',
                'bio' => 'Test customer account.',
            ],

            [
                'username' => 'vendor',
                'email' => 'vendor@example.com',
                'phone' => '9876543213',
                'password' => 'password',

                'first_name' => 'Test',
                'middle_name' => null,
                'last_name' => 'Vendor',
                'display_name' => 'Test Vendor',
                'date_of_birth' => '1988-03-10',
                'gender' => 'male',
                'bio' => 'Test vendor account.',
            ],
        ];

        DB::transaction(function () use ($users, $encryption) {

            foreach ($users as $data) {

                /*
                |--------------------------------------------------------------------------
                | User
                |--------------------------------------------------------------------------
                */

                $user = DB::table('users')
                    ->where('username', $data['username'])
                    ->first();

                if (!$user) {

                    $userId = DB::table('users')->insertGetId([
                        'uuid' => (string) Str::uuid(),

                        'username' => $data['username'],

                        /*
                         * Use existing encryption service.
                         */
                        'email_encrypted' => $encryption->encrypt(
                            $data['email']
                        ),

                        /*
                         * Blind index for exact lookup.
                         */
                        'email_hash' => $this->hashValue(
                            $data['email']
                        ),

                        'email_verified_at' => now(),

                        /*
                         * Use existing encryption service.
                         */
                        'phone_encrypted' => $encryption->encrypt(
                            $data['phone']
                        ),

                        /*
                         * Blind index for exact lookup.
                         */
                        'phone_hash' => $this->hashValue(
                            $data['phone']
                        ),

                        'phone_verified_at' => now(),

                        'password' => Hash::make(
                            $data['password']
                        ),

                        'status' => 'active',

                        'last_login_at' => null,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                } else {

                    /*
                     * Keep the existing UUID and identity.
                     * Do not regenerate UUID when seeder runs again.
                     */
                    $userId = $user->id;
                }

                /*
                |--------------------------------------------------------------------------
                | User Profile
                |--------------------------------------------------------------------------
                */

                $profileExists = DB::table('user_profiles')
                    ->where('user_id', $userId)
                    ->exists();

                if (!$profileExists) {

                    DB::table('user_profiles')->insert([
                        'user_id' => $userId,

                        /*
                         * Use existing encryption service.
                         */
                        'first_name_encrypted' => $encryption->encrypt(
                            $data['first_name']
                        ),

                        'middle_name_encrypted' => $encryption->encrypt(
                            $data['middle_name']
                        ),

                        'last_name_encrypted' => $encryption->encrypt(
                            $data['last_name']
                        ),

                        'display_name_encrypted' => $encryption->encrypt(
                            $data['display_name']
                        ),

                        'date_of_birth_encrypted' => $encryption->encrypt(
                            $data['date_of_birth']
                        ),

                        'gender' => $data['gender'],

                        'bio' => $data['bio'],

                        'profile_completion' => 100,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Generate deterministic hash for exact lookup.
     *
     * Encryption uses a random IV, so encrypted values
     * cannot be directly compared for searching.
     */
    private function hashValue(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return hash_hmac(
            'sha256',
            strtolower(trim($value)),
            config('app.key')
        );
    }
}