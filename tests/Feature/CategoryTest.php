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
        $expected = Category::first()->description;

        $response = $this->get(route('categories.index'));
        $response->assertSee($expected);
    }

    public function testCategoryCanBeCreated(): void
    {
        $category = Category::factory()->make();

        $response = $this->post(route('categories.store'), $category->toArray());

        $this->assertDatabaseHas('categories', $category->toArray());
    }
}