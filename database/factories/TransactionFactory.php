<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'name_other_party' => fake()->name(),
            'iban_other_party' => fake()->iban(),
            'payment_type' => Arr::random(['Credit Card', 'Debit Card', 'Manual Booking']),
            'purpose' => fake()->words(5, true),
            'value' => fake()->randomFloat(2, -10000, 10000),
            'balance_after' => fake()->randomFloat(2, -10000, 10000),
            'category_id' => Category::all()->random()->id
        ];
    }
}