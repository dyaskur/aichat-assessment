<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'first_name'        => "string",
        'last_name'         => "string",
        'email'             => "string",
        'email_verified_at' => "\Illuminate\Support\Carbon",
        'date_of_birth'     => "string",
        'gender'            => "mixed",
        'password'          => "string",
        'remember_token'    => "string",
        'contact_number'    => "string",
    ])] public function definition(): array
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'date_of_birth'     => $this->faker->date('Y-m-d', '-13 years'),
            'gender'            => $this->faker->randomElement(['male', 'female']),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
            'contact_number'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(function(array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
