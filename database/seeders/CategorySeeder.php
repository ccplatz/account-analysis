<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Arr::map(
            ['Rent', 'Salary', 'Shopping', 'Fun', 'Savings', 'Streaming and Media', 'Vacation'],
            fn($cat) => Category::create(['description' => $cat])
        );
    }
}