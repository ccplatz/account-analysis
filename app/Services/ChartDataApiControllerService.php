<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class ChartDataApiControllerService
{
    /**
     * Get the chart data for the request.
     *
     * @param  array $chartsConfig
     * @return array
     */
    public function getChartData(array $chartsConfig, int $year, int $month): array
    {
        $categoriesByMonthAndYear = $this->getCatsByMonth($year, $month);
        $data = ['categoriesByMonthAndYear' => $categoriesByMonthAndYear];

        $chartDescription = 'categoriesByPrevMonth';
        if ($this->chartIsRequired($chartDescription, $chartsConfig)) {
            $prevMonth = $this->getPrevMonthAndYear($year, $month)['month'];
            $prevMonthYear = $this->getPrevMonthAndYear($year, $month)['year'];
            $data[$chartDescription] = $this->getCatsByMonth($prevMonthYear, $prevMonth);
        }

        $chartDescription = 'categoriesAvgByLast3Month';
        if ($this->chartIsRequired($chartDescription, $chartsConfig)) {
            $data[$chartDescription] = $this->getCatsAvgByLast3Month($year, $month);
        }

        $chartDescription = 'categoriesByYear';
        if ($this->chartIsRequired($chartDescription, $chartsConfig)) {
            $data[$chartDescription] = $this->getCatsAverageByYear($year);
        }

        $chartDescription = 'categoriesByTotalTime';
        if ($this->chartIsRequired($chartDescription, $chartsConfig)) {
            $data[$chartDescription] = $this->getCatsAverageByTotalTime();
        }

        $chartDescription = 'categoriesByMonthPrevYear';
        if ($this->chartIsRequired($chartDescription, $chartsConfig)) {
            $data[$chartDescription] = $this->getCatsByMonth($year - 1, $month);
        }

        $categories = $this->getUniqueCats(...$data);
        $data['categories'] = $categories;

        return $data;
    }

    /**
     * Get values for every category by month.
     *
     * @param  mixed $year
     * @param  mixed $month
     * @return 
     */
    private function getCatsByMonth(int $year, int $month)
    {
        return Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereMonth('transactions.date', $month)
            ->whereYear('transactions.date', $year)
            ->select('categories.description as category', DB::raw('sum(transactions.value) as value'))
            ->groupBy('category')
            ->get();
    }

    /**
     * Get values for every category by month.
     *
     * @param  mixed $year
     * @param  mixed $month
     * @return 
     */
    private function getCatsAvgByLast3Month(int $actualYear, int $actualMonth)
    {
        $targetDate = Carbon::create($actualYear, $actualMonth, 1);
        $startDate = $targetDate->copy()->subMonths(3)->startOfMonth();
        $endDate = $targetDate->copy()->subMonths(1)->endOfMonth();

        return Transaction::join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereBetween('transactions.date', [$startDate, $endDate])
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
    private function getCatsAverageByYear(int $year)
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
    private function getCatsAverageByTotalTime()
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
    private function getUniqueCats(Collection ...$collections): array
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
    private function chartIsRequired($chartDescription, $chartsConfig): bool
    {
        return in_array($chartDescription, $chartsConfig);
    }

    /**
     * Get the previous month and year.
     *
     * @param  mixed $month
     * @param  mixed $year
     * @return array
     */
    private function getPrevMonthAndYear(int $year, int $month): array
    {
        return $month > 1 ? ['month' => $month - 1, 'year' => $year] : ['month' => 12, 'year' => $year - 1];
    }
}