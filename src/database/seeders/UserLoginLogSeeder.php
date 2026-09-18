<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserLoginAttempt;
use App\Models\UserLoginLog;
use Illuminate\Database\Seeder;

class UserLoginLogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::take(3)->get() as $user) {

            $attempt = UserLoginAttempt::where('user_id', $user->id)->first();

            UserLoginLog::create([
                'user_id' => $user->id,
                'login_attempt_uuid' => $attempt?->uuid,
                'email' => $user->email,
                'auth_method' => 'password',
                'status' => 'success',
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'logged_in_at' => now(),
            ]);

        }
    }
}