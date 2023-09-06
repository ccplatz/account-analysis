<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenFileException;
use App\Http\Requests\ChooseAccountRequest;
use App\Http\Requests\MapFieldsRequest;
use App\Http\Requests\StoreTransactionsRequest;
use App\Models\Account;
use App\Models\File;
use App\Services\CsvService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ImportCsvController extends Controller
{
    private ?CsvService $csvService = null;

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
    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
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
            $csvFields = $this->csvService->getFieldsFromFile($file);
        } catch (OpenFileException $e) {
            report($e);
            return redirect()->back()->withErrors($e->getMessage());
        }

        return view('import.map-fields')->with(
            [
                'fieldsToMap' => self::FIELDS_TO_MAP,
                'csvFields' => $csvFields,
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
        Arr::forget($validated, ['file', 'account']);
        $mappings = $validated;

        $this->csvService->storeTransactions($file, $account, $mappings);

        return redirect()->route('home');
    }
}