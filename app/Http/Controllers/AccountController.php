<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Category;
use App\Services\AccountControllerService;
use App\Services\GetTransactionsService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private GetTransactionsService $getTransactionsService;
    private AccountControllerService $accountControllerService;

    public function __construct(GetTransactionsService $getTransactionsService, AccountControllerService $accountControllerService)
    {
        $this->getTransactionsService = $getTransactionsService;
        $this->accountControllerService = $accountControllerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('accounts.index')->with('accounts', Account::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        try {
            Account::create($request->validated());
        } catch (QueryException $th) {
            return redirect()->back()->withErrors('An error occured. Account could not be created.');
        }

        return redirect()->back()->withSuccess('Account created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Account $account)
    {
        $month = $this->accountControllerService->getMonthFromRequest($request);
        $year = $this->accountControllerService->getYearFromRequest($request);
        $firstDateWithTransaction = DB::table('transactions')
            ->where('account_id', $account->id)
            ->min('date');
        $periodForYearDropdown = CarbonPeriod::create(
            Carbon::create($firstDateWithTransaction)->firstOfYear(),
            '1 year',
            now()->firstOfYear()
        );
        $periodForMonthDropdown = CarbonPeriod::create(
            now()->firstOfYear(),
            '1 month',
            now()->firstOfYear()->addMonths(11)
        );

        $transactionsPaginated = $this->getTransactionsService
            ->getTransactions($account, $month, $year)
            ->paginate(10)
            ->appends(
                request()->query()
            );

        return view('accounts.show')->with(
            [
                'account' => $account,
                'transactions' => $transactionsPaginated,
                'categories' => Category::all(),
                'month' => $month,
                'year' => $year,
                'periodForMonthDropdown' => $periodForMonthDropdown,
                'periodForYearDropdown' => $periodForYearDropdown,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        try {
            $account->delete();
        } catch (QueryException $th) {
            return redirect()->back()->withErrors('An error occured. Account could not be deleted.');
        }

        return redirect()->back()->withSuccess('Account deleted.');
    }
}