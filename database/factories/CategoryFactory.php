<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $moreThanTwoCharsValidator = fn($word) => Str::length($word) > 2;

        return [
            'description' => Str::ucfirst(fake()->valid($moreThanTwoCharsValidator)->words(1, true))
        ];
    }
}