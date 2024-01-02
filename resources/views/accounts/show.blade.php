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
            {{ __('Options') }}
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col">
                    <form action="{{ route('accounts.show', $account) }}" method="GET">
                        @csrf
                        <p>{{ __('Select transaction data') }}:</p>
                        <select class="form-select w-50 mb-3" id="monthSelect" name="month">
                            @foreach ($periodForMonthDropdown as $date)
                                <option value="{{ $date->format('n') }}" @selected($month == $date->format('n'))>
                                    {{ $date->format('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-select w-50 mb-3" id="yearSelect" name="year">
                            @foreach ($periodForYearDropdown as $date)
                                <option value="{{ $date->format('Y') }}" @selected($year == $date->format('Y'))>
                                    {{ $date->format('Y') }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary w-25" type="submit">{{ __('Select') }}</button>
                    </form>
                </div>
                <div class="col">
                    <p>{{ __('Select charts') }}:</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input charts-config-switch" onclick="return false;" type="checkbox"
                            value="categoriesByMonthAndYear" id="monthAndAndYearCheck" checked>
                        <label class="form-check-label" for="monthAndAndYearCheck">
                            {{ __('Categories by month and year') }}
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input charts-config-switch" type="checkbox" value="categoriesByYear"
                            id="yearCheck">
                        <label class="form-check-label" for="yearCheck">
                            {{ __('Categories average by year') }}
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input charts-config-switch" type="checkbox" value="categoriesByTotalTime"
                            id="totalTimeCheck">
                        <label class="form-check-label" for="totalTimeCheck">
                            {{ __('Categories average by total time') }}
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input charts-config-switch" type="checkbox"
                            value="categoriesBySameMonthYearBefore" id="sameMonthYearBeforeCheck">
                        <label class="form-check-label" for="sameMonthYearBeforeCheck">
                            {{ __('Categories by same month one year before') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card my-5">
        <div class="card-header">
            Statistics
        </div>

        <div class="card-body">

            <div id="chartWrapper" class="w-100"></div>

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
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->toArray()['date'] }}</a></td>
                            <td style="width: 10%">{{ $transaction->name_other_party }}</td>
                            <td>{{ $transaction->payment_type }}</td>
                            <td style="width: 20%">{{ $transaction->purpose }}</td>
                            <td>
                                <span class="text-{{ $transaction->value < 0 ? 'danger' : 'success' }}">
                                    {{ $transaction->valueGerman }} €
                                </span>
                            </td>
                            <td>
                                <span class="text-{{ $transaction->balance_after < 0 ? 'danger' : 'success' }}">
                                    {{ $transaction->balance_afterGerman }} €
                                </span>
                            </td>
                            <td>
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
                                    document.getElementById('delete-form-{{ $transaction->id }}').submit();">
                                    <i class="bi bi-trash3"></i>
                                </a>
                                <form id="delete-form-{{ $transaction->id }}" class="d-none"
                                    action="{{ route('transactions.destroy', $transaction) }}" method="POST">
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

@push('scripts')
    @vite('resources/js/accounts/show.js')
@endpush
