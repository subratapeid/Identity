<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::all() as $user) {

            UserProfile::create([
                'user_id' => $user->id,
                'date_of_birth' => fake()->date(),
                'gender' => fake()->randomElement([
                    'male',
                    'female',
                    'other',
                ]),
                'timezone' => 'Asia/Kolkata',
                'bio' => fake()->sentence(),
            ]);

        }
    }
}