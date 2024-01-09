<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Category;
use App\Models\ImportRule;
use Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImportRule>
 */
class ImportRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'account_id' => Account::first()->id,
            'field_name' => Arr::random(ImportRule::FIELD_NAMES),
            'pattern' => $this->faker->word,
            'exact_match' => 1,
            'category_id' => Category::first()->id,
        ];
    }
}
