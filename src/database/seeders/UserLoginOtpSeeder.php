<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserLoginOtp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserLoginOtpSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::take(3)->get() as $user) {

            UserLoginOtp::create([
                'user_id' => $user->id,
                'otp_hash' => Hash::make('123456'),
                'expires_at' => now()->addMinutes(5),
                'attempts' => 0,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);

        }
    }
}