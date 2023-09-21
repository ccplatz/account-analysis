@extends('layouts.app')

@section('content')
    <h1 class="text-center">Account overview</h1>
    <div class="card my-5">
        <div class="card-header">
            Account information
        </div>

        <div class="card-body">
            <b>Account:</b> {{ $account->description }}<br>
            <b>IBAN:</b> {{ $account->iban }}<br>
            <b>Bank:</b> {{ $account->bank }}<br>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Transactions
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Other party</th>
                        <th scope="col">Payment type</th>
                        <th scope="col">Purpose</th>
                        <th scope="col">Value</th>
                        <th scope="col">Balance after</th>
                        <th scope="col">Category</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($account->transactions as $transaction)
                        <tr>
                            <td width="3%">{{ $transaction->id }}</td>
                            <td width="10%">{{ $transaction->toArray()['date'] }}</a></td>
                            <td width="15%">{{ $transaction->name_other_party }}</td>
                            <td width="10%">{{ $transaction->payment_type }}</td>
                            <td>{{ $transaction->purpose }}</td>
                            <td width="10%">
                                <span class="text-{{ $transaction->value < 0 ? 'danger' : 'success' }}">
                                    {{ $transaction->valueGerman }} €
                                </span>
                            </td>
                            <td width="10%">
                                <span class="text-{{ $transaction->balance_after < 0 ? 'danger' : 'success' }}">
                                    {{ $transaction->balance_afterGerman }} €
                                </span>
                            </td>
                            <td width="15%">
                                {{ $transaction->category->description }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
