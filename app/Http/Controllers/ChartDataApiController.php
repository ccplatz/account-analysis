<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTransactionsApiRequest;
use App\Models\Transaction;
use DB;

class ChartDataApiController extends Controller
{
    public function categoryByMonth(GetTransactionsApiRequest $request)
    {
        $data = DB::table('transactions')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereMonth('transactions.date', $request->input('month'))
            ->whereYear('transactions.date', $request->input('year'))
            ->select('categories.description as category', DB::raw('sum(transactions.value) as value'))
            ->groupBy('category')
            ->get();

        return $data->toJson();
    }
}
