<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserProfileSeeder::class,
            UserLoginOtpSeeder::class,
            UserLoginAttemptSeeder::class,
            UserLoginLogSeeder::class,
            VisitorLogSeeder::class,
        ]);
    }
}