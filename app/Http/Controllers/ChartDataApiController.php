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

        $data = $this->service->getChartData($chartsConfig, $year, $month);

        return response($data);
    }
}
