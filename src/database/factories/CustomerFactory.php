<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * The corresponding model.
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerType = fake()->randomElement([
            'individual',
            'corporate',
        ]);

        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [

            'uuid' => (string) Str::uuid(),

            'customer_code' => 'CUS' . fake()->unique()->numerify('######'),

            'customer_type' => $customerType,

            'company_name' => $customerType === 'business'
                ? fake()->company()
                : null,

            'first_name' => $customerType === 'individual'
                ? $firstName
                : null,

            'name' => $customerType === 'individual'
                ? $lastName
                : fake()->company(),

            'email' => fake()->unique()->safeEmail(),

            'mobile' => '9' . fake()->unique()->numerify('#########'),

            'is_customer' => true,

            'is_active' => true,

            'created_by' => 1,

            'updated_by' => 1,
        ];
    }

    /**
     * Active customer.
     */
    public function active(): static
    {
        return $this->state(fn() => [
            'status' => 'active',
        ]);
    }

    /**
     * Business customer.
     */
    public function business(): static
    {
        return $this->state(function () {

            return [
                'customer_type' => 'business',
                'company_name' => fake()->company(),
                'first_name' => null,
                'name' => fake()->company(),
            ];

        });
    }

    /**
     * Individual customer.
     */
    public function individual(): static
    {
        return $this->state(function () {

            return [
                'customer_type' => 'individual',
                'company_name' => null,
                'first_name' => fake()->firstName(),
                'name' => fake()->lastName(),
            ];

        });
    }
}