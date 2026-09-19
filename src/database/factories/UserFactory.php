<?php

namespace Pagelyne\Identity\Database\Factories;

use Pagelyne\Identity\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),

            'name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'mobile' => fake()->unique()->numerify('##########'),

            'password' => static::$password ??= Hash::make('password'),

            'email_verified_at' => now(),

            'mobile_verified_at' => now(),

            'two_factor_enabled' => false,

            'status' => 'active',

            'remember_token' => Str::random(10),

            'created_by' => null,

            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}