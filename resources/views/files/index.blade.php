@extends('layouts.app')

@section('content')
    <div class="card my-5">
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

    <div class="card">
        <div class="card-header">
            Files
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Uploaded by</th>
                        <th scope="col">Size</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        <tr>
                            <td width="3%">{{ $file->id }}</td>
                            <td>{{ $file->name }}</td>
                            <td width="15%">{{ $file->user }}</td>
                            <td width="10%">{{ $file->size }}</td>
                            <td width="10%">{{ $file->type }}</td>
                            <td width="10%">
                                <a href="{{ route('files.delete', $file) }}"><i class="bi bi-trash3"></i></a>
                                <a href="{{ route('import.map-fields', $file) }}"><i class="bi bi-bar-chart"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
