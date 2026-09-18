<?php

namespace Database\Seeders;

use App\Models\VisitorLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisitorLogSeeder extends Seeder
{
    public function run(): void
    {
        VisitorLog::factory()->count(25)->create();

        // Or, if you aren't using a factory:

        /*
        for ($i = 0; $i < 25; $i++) {

            VisitorLog::create([
                'session_id' => Str::random(40),
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'referer' => fake()->url(),
                'landing_url' => fake()->url(),
                'current_url' => fake()->url(),
                'method' => fake()->randomElement(['GET','POST']),
                'country' => 'India',
                'state' => 'West Bengal',
                'city' => 'Kolkata',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'operating_system' => 'Windows',
                'is_bot' => false,
                'visited_at' => now(),
            ]);

        }
        */
    }
}