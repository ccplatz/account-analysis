<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Database\QueryException;

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
        return view('accounts.show')->with(
            [
                'account' => $account,
                'transactions' => $account->transactions
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