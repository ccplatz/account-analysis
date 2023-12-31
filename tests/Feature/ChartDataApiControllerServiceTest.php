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
                'value' => 40.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tJan3 = Transaction::factory()->create(
            [
                'date' => '2022-01-01',
                'value' => -20.00,
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

        $result = $service->getChartData(['categoriesByMonthAndYear'], 2022, 1);

        $this->assertEquals($expected->toArray(), $result['categoriesByMonthAndYear']->toArray());
    }

    public function testGetCategoryAverageByYear(): void
    {
        $service = app(ChartDataApiControllerService::class);
        $account = Account::factory()->create();
        $category = Category::factory()->create();

        $expected = collect(
            [
                [
                    'category' => $category->description,
                    'value' => 40.0,
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
                'value' => 240.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tFeb2 = Transaction::factory()->create(
            [
                'date' => '2022-02-01',
                'value' => -120.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tMrz1 = Transaction::factory()->create(
            [
                'date' => '2022-03-01',
                'value' => -120.00,
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

        $result = $service->getChartData(['categoriesByYear'], 2022, 1);

        $this->assertEquals($expected->toArray(), $result['categoriesByYear']->toArray());
    }

    public function testGetCategoryValuesByTotalTime(): void
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
                'value' => 40.00,
                'category_id' => $category->id,
                'account_id' => $account->id,
            ]
        );
        $tJan3 = Transaction::factory()->create(
            [
                'date' => '2022-01-01',
                'value' => -20.00,
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

        $result = $service->getChartData(['categoriesByTotalTime'], 2022, 1);

        $this->assertEquals($expected->toArray(), $result['categoriesByTotalTime']->toArray());
    }
}
