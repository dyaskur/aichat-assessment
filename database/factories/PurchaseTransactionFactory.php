<?php

namespace Database\Factories;

use App\Models\PurchaseTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class PurchaseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['total_spent' => "int"])] public function definition(): array
    {
        return [
            'total_spent' => $this->faker->randomNumber(3),
        ];
    }
}
