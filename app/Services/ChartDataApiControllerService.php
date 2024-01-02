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
        $monthlyData = Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereMonth('transactions.date', $month)
            ->whereYear('transactions.date', $year)
            ->select('categories.description as category', DB::raw('sum(transactions.value) as value'))
            ->groupBy('category')
            ->get();

        return $monthlyData;
    }

    /**
     * Get average values (every month with at least one transaction) for every category by year.
     *
     * @param  mixed $year
     * @return 
     */
    public function getCatsAverageByYear(int $year)
    {
        $yearlyData = Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereYear('transactions.date', $year)
            ->selectRaw('categories.description AS category, SUM(transactions.value) / COUNT(DISTINCT MONTH(transactions.date)) AS value')
            ->groupBy('category')
            ->get();

        return $yearlyData;
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
     * Return if categories by year dataset is required for the request.
     *
     * @param  mixed $chartsConfig
     * @return bool
     */
    public function catsByYearIsRequired($chartsConfig): bool
    {
        return in_array('categoriesByYear', $chartsConfig);
    }
}