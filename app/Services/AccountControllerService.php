<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountControllerService
{
    /**
     * Get the month form the requests query string.
     *
     * @param  mixed $request
     * @return int
     */
    public function getMonthFromRequest(Request $request): int
    {
        $month = $request->query('month');
        if ($month == '') {
            $month = Carbon::now()->month;
        }

        return $month;
    }

    /**
     * Get the year form the requests query string.
     *
     * @param  mixed $request
     * @return int
     */
    public function getYearFromRequest(Request $request): int
    {
        $year = $request->query('year');
        if ($year == '') {
            $year = Carbon::now()->year;
        }

        return $year;
    }

    /**
     * Get the year form the requests query string.
     *
     * @param  mixed $request
     * @return string
     */
    public function getFilterFromRequest(Request $request): string
    {
        $filter = $request->query('filter');
        if ($filter == '') {
            $filter = 'month';
        }

        return $filter;
    }
}