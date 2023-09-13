<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ImportService
{
    /**
     * Store transactions in the database.
     *
     * @param  mixed $file
     * @param  mixed $account
     * @param  mixed $mappings
     * @return void
     */
    public function storeTransactions(array $rawData, Account $account, array $mappings)
    {
        $mappedData = $this->applyMapping($rawData, $mappings);
        $transactions = $this->prepareDataForImport($mappedData, $account);

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }

        return;
    }

    /**
     * Apply mappings to the raw data.
     *
     * @param  mixed $rawData
     * @param  mixed $mappings
     * @return array
     */
    private function applyMapping(array $rawData, array $mappings): array
    {
        $mappedData = [];

        foreach ($rawData as $line) {
            $newLine = [];
            foreach ($mappings as $newKey => $oldKey) {
                $newLine[$newKey] = $line[$oldKey];
            }
            $mappedData[] = $newLine;
        }

        return $mappedData;
    }

    /**
     * Prepare data for import.
     *
     * @param  mixed $mappedData
     * @param  mixed $account
     * @return array
     */
    private function prepareDataForImport(array $mappedData, $account): array
    {
        $transactions = [];

        foreach ($mappedData as $data) {
            $data['date'] = Carbon::create($data['date']);
            $data['purpose'] = Str::squish($data['purpose']);
            $data['value'] = floatval(str_replace(",", ".", $data['value']));
            $data['balance_after'] = floatval($data['balance_after']);
            $data['account_id'] = $account->id;

            $transactions[] = $data;
        }

        return $transactions;
    }
}