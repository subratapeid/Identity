<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserLoginAttempt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserLoginAttemptSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::take(3)->get() as $user) {

            UserLoginAttempt::create([
                'user_id' => $user->id,
                'purpose' => 'login',
                'otp_hash' => Hash::make('123456'),
                'expires_at' => now()->addMinutes(5),
                'failed_attempts' => 0,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);

        }
    }
}