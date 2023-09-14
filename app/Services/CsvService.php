<?php

namespace App\Services;

use App\Exceptions\WrongValueCoundException;
use Illuminate\Support\Str;


class CsvService
{
    /**
     * @var string
     */
    private const CSV_SEPARATOR = ';'; // TODO: Add the possibility to change the separator

    /**
     * Get the fields of a given csv file.
     *
     * @param  mixed $file
     * @return array
     */
    public function getValuesFromCsvString(string $csvString): array
    {
        $fields = str_getcsv($csvString, self::CSV_SEPARATOR);

        return $fields;
    }

    /**
     * Get an array of array with header fields as keys and values.
     *
     * @param  mixed $lines
     * @return array
     */
    public function getAssociativeArrayFromLines(array $lines): array
    {
        $header = $this->getValuesFromCsvString(array_shift($lines));
        $csvData = [];

        foreach ($lines as $line) {
            if (!$this->isValidCsvLine($line)) {
                continue;
            }
            $values = $this->getValuesFromCsvString($line);
            $csvData[] = $this->mergeHeaderAndValues($header, $values);
        }

        return $csvData;
    }

    /**
     * Check if the given line is a valid csv line.
     *
     * @param  mixed $line
     * @return bool
     */
    private function isValidCsvLine($line): bool
    {
        return Str::contains($line, self::CSV_SEPARATOR);
    }

    /**
     * Merge the header fields and values to an array.
     *
     * @param  mixed $header
     * @param  mixed $values
     * @return array
     */
    private function mergeHeaderAndValues(array $header, array $values): array
    {
        if (count($values) !== count($header)) {
            throw new WrongValueCoundException('The number of values is not correct.');
        }

        return array_combine($header, $values);
    }
}