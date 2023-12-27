<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class GetTransactionsService
{
    /**
     * Get transaction of the account by month.
     *
     * @param  mixed $account
     * @param  mixed $month
     * @param  mixed $year
     */
    public function getByMonth(Account $account, int $month = null, int $year = null)
    {
        if (!$month) {
            $month = Carbon::now()->month;
        }

        if (!$year) {
            $year = Carbon::now()->year;
        }

        return Transaction::
            where('account_id', $account->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year);
    }
}