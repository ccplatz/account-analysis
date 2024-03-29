<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testCategoryIsMenuItem(): void
    {
        $response = $this->get(route('home'));
        $response->assertSee('Categories');
    }

    public function testCategoriesShownOnIndex(): void
    {
        $category = Category::factory()->create();
        $expected = Category::first()->description;

        $response = $this->get(route('categories.index'));
        $response->assertSee($expected);
    }

    public function testCategoryCanBeCreated(): void
    {
        $expected = Category::factory()->make();

        $response = $this->post(route('categories.store'), $expected->toArray());

        $this->assertDatabaseHas('categories', $expected->toArray());
    }

    public function testCategorySumShownOnOverview(): void
    {
        $category = Category::factory()->create();
        $expected = number_format(Category::first()->transactions->sum('value'), 2, ',', '.');

        $response = $this->get(route('categories.index'));

        $response->assertSee($expected);
    }

    public function testCategoryNumberOfTransactionsShownOnOverview(): void
    {
        $category = Category::factory()->create();
        $expected = Category::first()->transactions->count();

        $response = $this->get(route('categories.index'));

        $response->assertSee($expected);
    }
}