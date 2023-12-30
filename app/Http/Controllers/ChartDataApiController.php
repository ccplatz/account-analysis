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
     * Get chart data for category by month.
     *
     * @param  mixed $request
     * @return void
     */
    public function categoryByMonth(GetTransactionsApiRequest $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $monthlyValues = $this->service->getCategoryByMonth($year, $month);
        $yearlyValues = $this->service->getCategoryAverageByYear($year);
        $categories = $this->service->getUniqueCategories($monthlyValues, $yearlyValues);
        $data = collect([$categories, $monthlyValues, $yearlyValues])->toJson();

        return response($data);
    }
}
