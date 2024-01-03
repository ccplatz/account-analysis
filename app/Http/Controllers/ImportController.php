<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenFileException;
use App\Exceptions\WrongValueCountException;
use App\Http\Requests\ChooseAccountRequest;
use App\Http\Requests\MapFieldsRequest;
use App\Http\Requests\StoreTransactionsRequest;
use App\Models\Account;
use App\Models\File;
use App\Services\CsvService;
use App\Services\GetFileContentService;
use App\Services\ImportService;
use Brick\Math\Exception\NumberFormatException;

class ImportController extends Controller
{
    /**
     *  @var CsvService
     */
    private ?CsvService $csvService = null;

    /**
     * @var GetFileContentService
     */
    private ?GetFileContentService $getFileContentService = null;

    /**
     * @var ImportService
     */
    private ?ImportService $importService = null;

    private const FIELDS_TO_MAP = [
        'date',
        'name_other_party',
        'iban_other_party',
        'payment_type',
        'purpose',
        'value',
        'balance_after'
    ];

    /**
     * __construct
     *
     * @param  mixed $csvService
     * @return void
     */
    public function __construct(CsvService $csvService, GetFileContentService $getFileContentService, ImportService $importService)
    {
        $this->csvService = $csvService;
        $this->getFileContentService = $getFileContentService;
        $this->importService = $importService;
    }

    /**
     * Choose the account to import transactions.
     *
     * @param  mixed $request
     * @return void
     */
    public function chooseAccount(ChooseAccountRequest $request)
    {
        return view('import.choose-account')->with(
            [
                'file' => File::findOrFail($request->validated()['file']),
                'accounts' => Account::all()
            ]
        );
    }

    /**
     * mapFields
     *
     * @param  mixed $file
     * @return void
     */
    public function mapFields(MapFieldsRequest $request)
    {
        $file = File::findOrFail($request->validated()['file']);
        $account = Account::findOrFail($request->validated()['account']);

        try {
            $lines = $this->getFileContentService->getLinesFromFile($file);
        } catch (OpenFileException $e) {
            report($e);
            return redirect()->back()->withErrors($e->getMessage());
        }
        $headerFieldsFromCsvFile = $this->csvService->getValuesFromCsvString($lines[0]);

        return view('import.map-fields')->with(
            [
                'fieldsToMap' => self::FIELDS_TO_MAP,
                'headerFieldsFromCsvFile' => $headerFieldsFromCsvFile,
                'file' => $file,
                'account' => $account,
            ]
        );
    }

    public function storeTransactions(StoreTransactionsRequest $storeTransactionsRequest)
    {
        $validated = $storeTransactionsRequest->validated();
        $file = File::findOrFail($validated['file']);
        $account = Account::findOrFail($validated['account']);
        $mappings = $storeTransactionsRequest->safe()->except(['file', 'account']);

        $lines = $this->getFileContentService->getLinesFromFile($file);
        $rawData = $this->csvService->getAssociativeArrayFromLines($lines);
        try {
            $this->importService->storeTransactions($rawData, $account, $mappings);
        } catch (NumberFormatException $e) {
            report($e);
            return redirect()->back()->withErrors($e->getMessage());
        } catch (WrongValueCountException $e) {
            report($e);
            return redirect()->back()->withErrors($e->getMessage());
        }
        $file->setToImported();

        return redirect()->route('accounts.show', $account)->withSuccess('File imported.');
    }
}