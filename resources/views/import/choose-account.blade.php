@extends('layouts.app')

@section('content')
    <h1 class="text-center">Choose account for transaction import</h1>

    <div class="card my-5">
        <div class="card-header">Accounts</div>

        <div class="card-body">
            <form action="{{ route('import.map-fields') }}" method="POST">
                @csrf
                <input type="hidden" name="file" value="{{ $file->id }}">
                <div class="mb-3">
                    <label for="account" class="form-label">Account</label>:
                    <select class="form-select" name="account" id="account">
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->description }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Choose Account</button>
            </form>
        </div>
    </div>
@endsection
