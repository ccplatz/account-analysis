@extends('layouts.app')

@section('content')
    <h1 class="text-center">Categories</h1>

    <div class="card my-5">
        <div class="card-header">Create new category</div>

        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                        placeholder="Category description" value="{{ old('description') }}">
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
                        <th scope="col">Description</th>
                        <th scope="col">Number of assigned transactions</th>
                        <th scope="col">Saldo</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td width="3%">{{ $category->id }}</td>
                            <td>{{ $category->description }}</td>
                            <td>{{ $category->transactions->count() }}</td>
                            <td width="15%">
                                <span class="text-{{ $category->transactions->sum('value') < 0 ? 'danger' : 'success' }}">
                                    {{ number_format($category->transactions->sum('value'), 2, ',', '.') }}
                                </span>
                            </td>
                            <td width="10%" class="fs-5">
                                <a href="{{ route('categories.destroy', $category) }}"
                                    onclick="event.preventDefault();
                                document.getElementById('delete-category-{{ $category->id }}').submit();"><i
                                        class="bi bi-trash3"></i></a>
                                <form id="delete-category-{{ $category->id }}"
                                    action="{{ route('categories.destroy', $category) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="file" value="{{ $category->id }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
