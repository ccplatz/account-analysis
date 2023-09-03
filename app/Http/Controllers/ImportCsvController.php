<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenFileException;
use App\Models\File;
use App\Services\CsvService;
use Illuminate\Http\Request;

class ImportCsvController extends Controller
{
    private ?CsvService $csvService = null;

    private const FIELDS_TO_MAP = [
        'date',
        'name_other_party',
        'payment_type',
        'purpose',
        'amount',
        'negative',
        'balance_after'
    ];

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
    }

    public function mapFields(File $file)
    {
        try {
            $csvFields = $this->csvService->getFieldsFromFile($file);
        } catch (OpenFileException $e) {
            report($e);
            return redirect()->back()->withErrors($e->getMessage());
        }

        return view('import.map-fields')->with(
            [
                'fieldsToMap' => self::FIELDS_TO_MAP,
                'csvFields' => $csvFields
            ]
        );
    }
}