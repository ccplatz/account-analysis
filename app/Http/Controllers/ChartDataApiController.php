<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTransactionsApiRequest;
use App\Models\Transaction;
use App\Services\ChartDataApiControllerService;
use DB;

class ChartDataApiController extends Controller
{
    private ChartDataApiControllerService $service;

    public function __construct(ChartDataApiControllerService $service)
    {
        $this->service = $service;
    }

    /**
     * Get chart data like requested.
     *
     * @param  mixed $request
     */
    public function transactionsByCategory(GetTransactionsApiRequest $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $chartsConfig = $request->input('chartsConfig');

        $categoriesByMonthAndYear = $this->service->getCatsByMonth($year, $month);
        $data = ['categoriesByMonthAndYear' => $categoriesByMonthAndYear];

        if ($this->service->catsByYearIsRequired($chartsConfig)) {
            $data['categoriesByYear'] = $this->service->getCatsAverageByYear($year);
        }

        if ($this->service->catsByTotalTimeIsRequired($chartsConfig)) {
            $data['categoriesByTotalTime'] = $this->service->getCatsAverageByTotalTime();
        }

        if ($this->service->catsByMonthPrevYearIsRequired($chartsConfig)) {
            $data['categoriesByMonthPrevYear'] = $this->service->getCatsByMonth($year - 1, $month);
        }

        $categories = $this->service->getUniqueCats(...$data);
        $data['categories'] = $categories;

        return response($data);
    }
}
