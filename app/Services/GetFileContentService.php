<?php

namespace App\Services;

use App\Exceptions\OpenFileException;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class GetFileContentService
{
    /**
     * Get the lines from a file.
     *
     * @param  mixed $file
     * @return array
     */
    public function getLinesFromFile(File $file): array
    {
        $contents = Storage::get($file->path);
        if (empty($contents)) {
            throw new OpenFileException('File could not be opened.');
        }
        $lines = explode(PHP_EOL, $contents);

        return $lines;
    }
}