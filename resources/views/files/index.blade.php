@extends('layouts.app')

@section('content')
    <h1 class="text-center">Import files</h1>

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
                            <td width="10%" class="fs-5">
                                <a href="{{ route('files.delete', $file) }}"><i class="bi bi-trash3"></i></a>
                                @if ($file->isImported())
                                    <i class="bi bi-database-check"></i>
                                @else
                                    <a href="{{ route('import.choose-account') }}"
                                        onclick="event.preventDefault();
                                document.getElementById('import-file-{{ $file->id }}').submit();">
                                        <i class="bi bi-database-add"></i>
                                    </a>
                                    <form id="import-file-{{ $file->id }}"
                                        action="{{ route('import.choose-account') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="file" value="{{ $file->id }}">
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
