<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(session()->has('success'));
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
    public function show(Account $account)
    {
        $options = [
            'chart_title' => 'Test',
            'chart_type' => 'bar',
            'report_type' => 'group_by_relationship',
            'model' => Transaction::class,
            'relationship_name' => 'category',
            'group_by_field' => 'description',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'value',
            'where_raw' => 'account_id = ' . $account->id,
            'chart_color' => '124,12,37',
        ];
        $chart = new LaravelChart($options);

        return view('accounts.show')->with(
            [
                'account' => $account,
                'categories' => Category::all(),
                'chart' => $chart
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