@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Upload new file</div>

        <div class="card-body">
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="formFile" class="form-label">Input csv-file with transactions</label>
                    <input type="file" name="file" class="form-control"
                        accept=".jpg,.jpeg,.bmp,.png,.gif,.doc,.docx,.csv,.rtf,.xlsx,.xls,.txt,.pdf,.zip">
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>
@endsection
