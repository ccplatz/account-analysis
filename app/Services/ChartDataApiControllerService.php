<?php

namespace App\Services;

use App\Models\Transaction;
use DB;
use Illuminate\Support\Collection;

class ChartDataApiControllerService
{
    /**
     * Get values for every category by month.
     *
     * @param  mixed $year
     * @param  mixed $month
     * @return 
     */
    public function getCatsByMonth(int $year, int $month)
    {
        return Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereMonth('transactions.date', $month)
            ->whereYear('transactions.date', $year)
            ->select('categories.description as category', DB::raw('sum(transactions.value) as value'))
            ->groupBy('category')
            ->get();
    }

    /**
     * Get average values (every month with at least one transaction) for every category by year.
     *
     * @param  mixed $year
     * @return 
     */
    public function getCatsAverageByYear(int $year)
    {
        return Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereYear('transactions.date', $year)
            ->selectRaw('categories.description AS category, SUM(transactions.value) / COUNT(DISTINCT MONTH(transactions.date)) AS value')
            ->groupBy('category')
            ->get();
    }

    /**
     * Get average values (every month with at least one transaction) for every category by total time.
     *
     * @param  mixed $year
     * @return 
     */
    public function getCatsAverageByTotalTime()
    {
        return Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->selectRaw('categories.description AS category, SUM(transactions.value) / COUNT(DISTINCT FORMAT(transactions.date, "yyyyMM")) AS value')
            ->groupBy('category')
            ->get();
    }

    /**
     * Get an array of unique categories from the collections.
     *
     * @param  mixed $data1
     * @param  mixed $data2
     * @return array
     */
    public function getUniqueCats(Collection ...$collections): array
    {
        $categories = collect();

        foreach ($collections as $collection) {
            $categories = $categories->merge($collection->pluck('category')->all());
        }

        return $categories->unique()->flatten()->all();
    }

    /**
     * Return if categories by previous month dataset is required for the request.
     *
     * @param  mixed $chartsConfig
     * @return bool
     */
    public function catsByPrevMonthIsRequired($chartsConfig): bool
    {
        return in_array('categoriesByPrevMonth', $chartsConfig);
    }

    /**
     * Return if categories by year dataset is required for the request.
     *
     * @param  mixed $chartsConfig
     * @return bool
     */
    public function catsByYearIsRequired($chartsConfig): bool
    {
        return in_array('categoriesByYear', $chartsConfig);
    }

    /**
     * Return if categories by total time dataset is required for the request.
     *
     * @param  mixed $chartsConfig
     * @return bool
     */
    public function catsByTotalTimeIsRequired($chartsConfig): bool
    {
        return in_array('categoriesByTotalTime', $chartsConfig);
    }

    /**
     * Return if categories by month of previous year dataset is required for the request.
     *
     * @param  mixed $chartsConfig
     * @return bool
     */
    public function catsByMonthPrevYearIsRequired($chartsConfig): bool
    {
        return in_array('categoriesByMonthPrevYear', $chartsConfig);
    }

    /**
     * Get the previous month and year.
     *
     * @param  mixed $month
     * @param  mixed $year
     * @return array
     */
    public function getPrevMonthAndYear(int $year, int $month): array
    {
        return $month > 1 ? ['month' => $month - 1, 'year' => $year] : ['month' => 12, 'year' => $year - 1];
    }
}