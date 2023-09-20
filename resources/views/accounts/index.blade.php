@extends('layouts.app')

@section('content')
    <div class="card my-5">
        <div class="card-header">Create new account</div>

        <div class="card-body">
            <form action="{{ route('accounts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                        placeholder="Primary account" value="{{ old('description') }}">
                </div>
                <div class="mb-3">
                    <label for="iban" class="form-label">IBAN</label>
                    <input type="text" class="form-control" id="iban" name="iban"
                        placeholder="DE8901234567981321" value="{{ old('iban') }}">
                </div>
                <div class="mb-3">
                    <label for="bank" class="form-label">Bank</label>
                    <input type="text" class="form-control" id="bank" name="bank" placeholder="Bank ABC"
                        value="{{ old('bank') }}">
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Accounts
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Description</th>
                        <th scope="col">IBAN</th>
                        <th scope="col">Bank</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td width="3%">{{ $account->id }}</td>
                            <td><a href="{{ route('accounts.show', $account) }}">{{ $account->description }}</a></td>
                            <td width="15%">{{ $account->iban }}</td>
                            <td width="20%">{{ $account->bank }}</td>
                            <td width="10%">
                                <a href="{{ route('accounts.destroy', $account) }}"
                                    onclick="event.preventDefault();
                                document.getElementById('delete-form-{{ $account->id }}').submit();"><i
                                        class="bi bi-trash3"></i></a>
                                <form id="delete-form-{{ $account->id }}"
                                    action="{{ route('accounts.destroy', $account) }}" method="POST"
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
