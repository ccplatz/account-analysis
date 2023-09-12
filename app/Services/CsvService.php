<?php

namespace App\Services;

use App\Exceptions\OpenFileException;
use App\Exceptions\WrongFieldCoundException;
use App\Models\Account;
use App\Models\File;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CsvService
{
    /**
     * Get the fields of a given csv file.
     *
     * @param  mixed $file
     * @return array
     */
    public function getFieldsFromFile(File $file): array
    {
        $lines = $this->getLinesFromFile($file);
        $firstLine = $lines[0];

        $fields = str_getcsv($firstLine, ';'); // TODO: Add possibility to change separator

        return $fields;
    }

    public function storeTransactions(File $file, Account $account, array $mappings)
    {
        $lines = $this->getLinesFromFile($file);
        $header = str_getcsv(array_shift($lines), ';');
        $exportedLines = [];
        $reducedData = [];
        $transactions = [];

        // read from csv file
        foreach ($lines as $line) {
            if (!Str::contains($line, ';')) {
                continue;
            }

            $fields = str_getcsv($line, ';'); // TODO: Add possibility to change separator

            if (count($fields) !== count($header)) {
                throw new WrongFieldCoundException('The number of fields is not correct.');
            }

            $exportedLines[] = array_combine($header, $fields);
        }

        // apply mapping
        foreach ($exportedLines as $line) {
            $newLine = [];
            foreach ($mappings as $newKey => $oldKey) {
                $newLine[$newKey] = $line[$oldKey];
            }
            $reducedData[] = $newLine;
        }

        // prepare data for import
        foreach ($reducedData as $data) {
            $data['date'] = Carbon::create($data['date']);
            $data['purpose'] = Str::squish($data['purpose']);
            $data['value'] = floatval(str_replace(",", ".", $data['value']));
            $data['balance_after'] = floatval($data['balance_after']);
            $data['account_id'] = $account->id;

            $transactions[] = $data;
        }

        // save transactions
        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
        // Transaction::insert($transactions);

        return;
    }

    /**
     * Get the lines from a file.
     *
     * @param  mixed $file
     * @return array
     */
    private function getLinesFromFile(File $file): array
    {
        $contents = Storage::get($file->path);
        if (empty($contents)) {
            throw new OpenFileException('File could not be opened.');
        }
        $lines = explode(PHP_EOL, $contents);

        return $lines;
    }
}