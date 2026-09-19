<?php

namespace Pagelyne\Identity\Database\Factories;

use Pagelyne\Identity\Models\VisitorLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<VisitorLog>
 */
class VisitorLogFactory extends Factory
{
    /**
     * The name of the corresponding model.
     */
    protected $model = VisitorLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $methods = ['GET', 'POST'];

        $browsers = [
            'Chrome',
            'Edge',
            'Firefox',
            'Safari',
            'Opera',
        ];

        $devices = [
            'Desktop',
            'Mobile',
            'Tablet',
        ];

        $operatingSystems = [
            'Windows',
            'Android',
            'iOS',
            'macOS',
            'Linux',
        ];

        $country = 'India';

        $state = fake()->randomElement([
            'West Bengal',
            'Odisha',
            'Jharkhand',
            'Bihar',
            'Maharashtra',
            'Karnataka',
            'Tamil Nadu',
            'Delhi',
        ]);

        $city = fake()->city();

        return [

            'uuid' => (string) Str::uuid(),

            'session_id' => Str::random(40),

            'ip_address' => fake()->ipv4(),

            'user_agent' => fake()->userAgent(),

            'referer' => fake()->optional()->url(),

            'landing_url' => fake()->url(),

            'current_url' => fake()->url(),

            'method' => fake()->randomElement($methods),

            'country' => $country,

            'state' => $state,

            'city' => $city,

            'device' => fake()->randomElement($devices),

            'browser' => fake()->randomElement($browsers),

            'operating_system' => fake()->randomElement($operatingSystems),

            'is_bot' => fake()->boolean(5),

            'visited_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}