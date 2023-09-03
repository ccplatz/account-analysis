<?php

namespace App\Services;

use App\Exceptions\OpenFileException;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

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

        $contents = Storage::get($file->path);
        if (empty($contents)) {
            throw new OpenFileException('File could not be opened.');
        }
        $firstLine = explode(PHP_EOL, $contents)[0];

        $fields = str_getcsv($firstLine, ';');

        return $fields;
    }
}