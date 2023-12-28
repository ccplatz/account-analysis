@extends('layouts.app')

@section('content')
    <h1 class="text-center">Account overview</h1>
    <div class="card my-5">
        <div class="card-header">
            Account information
        </div>

        <div class="card-body">
            <p>
                <b>Account:</b> {{ $account->description }}<br>
                <b>IBAN:</b> {{ $account->iban }}<br>
                <b>Bank:</b> {{ $account->bank }}<br>
            </p>
        </div>
    </div>

    <div class="card my-5">
        <div class="card-header">
            Select data
        </div>
        <div class="card-body">
            <form action="{{ route('accounts.show', $account) }}" method="GET">
                @csrf
                <div class="row mb-4">
                    <div class="col">
                        <select class="form-select" id="monthSelect" name="month">
                            @foreach ($periodForMonthDropdown as $date)
                                <option value="{{ $date->format('n') }}" @selected($month == $date->format('n'))>
                                    {{ $date->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select" id="yearSelect" name="year">
                            @foreach ($periodForYearDropdown as $date)
                                <option value="{{ $date->format('Y') }}" @selected(now()->year == $date->format('Y'))>
                                    {{ $date->format('Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1"><button class="btn btn-primary w-100" type="submit">Filter</a></div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Transactions
        </div>

        <div class="card-body">
            {{ $transactions->links() }}
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
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
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
                                <x-select id="categorySelect-{{ $transaction->id }}" class="category__select form-select"
                                    :options="$categories" :selected="$transaction->category">
                                    <option value="">Set category</option>
                                </x-select>
                                <input type="hidden" name="transaction"
                                    id="transactionCategorySelect-{{ $transaction->id }}" value="{{ $transaction->id }}">
                            </td>
                            <td class="fs-5">
                                <a href="{{ route('transactions.destroy', $transaction) }}"
                                    onclick="event.preventDefault();
                                document.getElementById('delete-form-{{ $transaction->id }}').submit();"><i
                                        class="bi bi-trash3"></i></a>
                                <form id="delete-form-{{ $transaction->id }}"
                                    action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
