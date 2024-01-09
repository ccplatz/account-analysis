@extends('layouts.app')

@section('content')
    <h1 class="text-center">Import Rules</h1>

    <div class="card my-5">
        <div class="card-header">Create new rule</div>

        <div class="card-body">
            <form action="{{ route('import-rules.store') }}" method="post">
                @csrf
                <div class="mb-3 w-25">
                    <label for="descriptionText" class="form-label">{{ __('Description of the rule') }}</label>
                    <input type="text" class="form-control" id="descriptionText" name="description">
                </div>
                <div class="mb-3 w-25">
                    <label for="accountSelect" class="form-label">{{ __('Set account') }}</label>
                    <x-select id="accountSelect" name="account_id" class="account__select form-select" :options="$accounts"
                        :selected="null">
                    </x-select>
                </div>
                <div class="mb-3 w-25">
                    <label for="fieldSelect" class="form-label">{{ __('Set field to check') }}</label>
                    <select id="fieldSelect" name="field_name" class="field__select form-select">
                        @foreach ($fields as $value => $fieldName)
                            <option value="{{ $value }}">{{ __($fieldName) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-1 w-25">
                    <label for="patternText" class="form-label">{{ __('Pattern to look for') }}</label>
                    <input type="text" class="form-control" id="patternText" name="pattern">
                </div>
                <div class="form-check mb-3 w-25">
                    <input type="checkbox" class="form-check-input" id="exactMatchCheck" name="exact_match" value="1"
                        checked>
                    <label for="exactMatchCheck" class="form-check-label">{{ __('Find exact match') }}</label>
                </div>
                <div class="mb-4 w-25">
                    <label for="categorySelect" name="category_id"
                        class="form-label">{{ __('Choose category to set') }}</label>
                    <x-select id="categorySelect" name="category_id" class="category__select form-select" :options="$categories"
                        :selected="null">
                    </x-select>
                </div>
                <div class="mb-3 w-25">
                    <button type="submit" class="btn btn-primary">{{ __('Create new import rule') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header">
            Import rules
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Description') }}</th>
                        <th scope="col">{{ __('Account') }}</th>
                        <th scope="col">{{ __('Field') }}</th>
                        <th scope="col">{{ __('Pattern') }}</th>
                        <th scope="col">{{ __('Exact match') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($importRules as $rule)
                        <tr>
                            <td>{{ $rule->description }}</td>
                            <td>{{ $rule->account->description }}</td>
                            <td>{{ $rule->field_name_public }}</td>
                            <td>{{ Str::limit($rule->pattern, 20, '...') }}</td>
                            <td>{{ $rule->exact_match }}</td>
                            <td>{{ $rule->category->description }}</td>
                            <td>
                                <a href="{{ route('import-rules.destroy', $rule) }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('delete-form-{{ $rule->id }}').submit();">
                                    <i class="bi bi-trash3"></i>
                                </a>
                                <form id="delete-form-{{ $rule->id }}" class="d-none"
                                    action="{{ route('import-rules.destroy', $rule) }}" method="POST">
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
