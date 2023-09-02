@extends('layouts.app')

@section('content')
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
                            <td width="5%"><a href="{{ route('files.delete', $file) }}"
                                    class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
