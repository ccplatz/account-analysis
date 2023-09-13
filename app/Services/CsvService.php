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

    public function getAssociativeArrayFromLines(array $lines): array
    {
        $header = str_getcsv(array_shift($lines), ';');
        $csvData = [];

        foreach ($lines as $line) {
            if (!Str::contains($line, self::CSV_SEPARATOR)) {
                continue;
            }

            $values = str_getcsv($line, self::CSV_SEPARATOR);

            if (count($values) !== count($header)) {
                throw new WrongValueCoundException('The number of values is not correct.');
            }

            $csvData[] = array_combine($header, $values);
        }

        return $csvData;
    }
}