<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code'       => $this->faker->unique()->regexify('[A-Za-z0-9]{20}'),
            'discount'   => $this->faker->randomNumber(2),
            'name'       => "Promo of ".$this->faker->word(),
            'start_date' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'end_date'   => $this->faker->dateTimeBetween('+1 years', '+1 years'),
        ];
    }
}
