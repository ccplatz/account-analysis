<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ImportRule;
use App\Models\Transaction;
use Brick\Math\Exception\NumberFormatException;
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
        $transactionsData = $this->prepareDataForImport($mappedData, $account);
        $importRules = ImportRule::where('account_id', $account->id)->orderBy('sequence', 'desc')->get();

        foreach ($transactionsData as $transactionData) {
            $transaction = Transaction::create($transactionData);
            foreach ($importRules as $rule) {
                if ($rule->applies($transaction)) {
                    $rule->apply($transaction);
                }
            }
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
            $mappedData[] = $this->buildNewLine($mappings, $line);
        }

        return $mappedData;
    }

    /**
     * Build a new line from mapping and raw data.
     *
     * @param  mixed $mappings
     * @param  mixed $line
     * @return array
     */
    private function buildNewLine(array $mappings, array $line): array
    {
        $newLine = [];
        foreach ($mappings as $newKey => $oldKey) {
            $newLine[$newKey] = $line[$oldKey];
        }

        return $newLine;
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
            $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->toDateString();
            $data['purpose'] = Str::squish($data['purpose']);
            $data['value'] = $this->getFloatFromGermanFormatedNumberString($data['value']);
            $data['balance_after'] = $this->getFloatFromGermanFormatedNumberString($data['balance_after']);
            $data['account_id'] = $account->id;

            $transactions[] = $data;
        }

        return $transactions;
    }

    /**
     * Get float from german decimal format.
     *
     * @param  mixed $value
     * @return float
     */
    private function getFloatFromGermanFormatedNumberString($value): float
    {
        $formatedValue = Str::replace(".", "", $value);
        $formatedValue = Str::replace(",", ".", $formatedValue);
        if (!is_numeric($formatedValue)) {
            throw new NumberFormatException('The given value is not a valid number: ' . $value);
        }
        return floatval($formatedValue);
    }
}