<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\ChartDataApiControllerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChartDataApiControllerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCategoryAverageByYear(): void
    {
        $service = app(ChartDataApiControllerService::class);
        $account = Account::factory()->create();
        $category = Category::factory()->create();

        $expected = collect(
            [
                [
                    'category' => $category->description,
                    'value' => 120.0,
                ]
            ]
        );

        $tJan1 = Transaction::factory()->create(
            [
                'date' => '2022-01-01',
                'value' => 100.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tJan2 = Transaction::factory()->create(
            [
                'date' => '2022-01-01',
                'value' => 20.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tFeb1 = Transaction::factory()->create(
            [
                'date' => '2022-02-01',
                'value' => 120.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tJan2021 = Transaction::factory()->create(
            [
                'date' => '2021-01-01',
                'value' => 120.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );

        $this->assertEquals($expected->toArray(), $service->getCategoryAverageByYear(2022)->toArray());
    }

    public function testGetCategoryValuesByMonth(): void
    {
        $service = app(ChartDataApiControllerService::class);
        $account = Account::factory()->create();
        $category = Category::factory()->create();

        $expected = collect(
            [
                [
                    'category' => $category->description,
                    'value' => 120.0,
                ]
            ]
        );

        $tJan1 = Transaction::factory()->create(
            [
                'date' => '2022-01-01',
                'value' => 100.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tJan2 = Transaction::factory()->create(
            [
                'date' => '2022-01-01',
                'value' => 20.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tFeb1 = Transaction::factory()->create(
            [
                'date' => '2022-02-01',
                'value' => 120.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tJan2021 = Transaction::factory()->create(
            [
                'date' => '2021-01-01',
                'value' => 120.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );

        $this->assertEquals($expected->toArray(), $service->getCategoryByMonth(2022, 1)->toArray());
    }
}
