<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class GetTransactionsService
{
    public function getTransactions(Account $account, string $filter, int $month = null, int $year = null)
    {
        if (!$month) {
            $month = Carbon::now()->month;
        }

        if (!$year) {
            $year = Carbon::now()->year;
        }

        if ($filter === 'month') {
            return $this->getByMonth($account, $month, $year);
        }

        return $this->getByYear($account, $year);
    }

    /**
     * Get transaction of the account by year.
     *
     * @param  mixed $account
     * @param  mixed $month
     * @param  mixed $year
     */
    private function getByYear(Account $account, int $year = null)
    {
        return Transaction::where('account_id', $account->id)
            ->whereYear('date', $year);
    }

    /**
     * Get transaction of the account by month.
     *
     * @param  mixed $account
     * @param  mixed $month
     * @param  mixed $year
     */
    private function getByMonth(Account $account, int $month = null, int $year = null)
    {
        return Transaction::where('account_id', $account->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year);
    }
}