@extends('layouts.app')

@section('content')
    <h1 class="text-center">CSV field mapping</h1>
    <div class="card my-5">
        <div class="card-header">Mappings</div>

        <div class="card-body">
            <form action="{{ route('import.save-mapping') }}" method="POST">
                @csrf
                @foreach ($fieldsToMap as $field)
                    <div class="mb-3">
                        <label for="{{ $field }}" class="form-label">{{ $field }}</label>:
                        <select class="form-select" name="{{ $field }}" id="{{ $field }}">
                            @foreach ($csvFields as $csvField)
                                <option value="{{ $csvField }}">{{ $csvField }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>
@endsection
